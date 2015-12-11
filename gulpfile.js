var path = {
	pkg: 'package.json',
	src: 'src',
	docs: 'docs',
	docsConf: 'apigen.neon'
};

var pkg = require('./' + path.pkg);
var gulp = require('gulp');
var g = require('gulp-load-plugins')();
var series = require('run-sequence'); // TODO Replace with gulp.series on Gulp 4.0 release
var bsync = require('browser-sync');
var shell = require('child_process').exec;
var del = require('del');
var util = require('util');
var fs = require('fs');

gulp.task('default', ['build']);
gulp.task('build', ['docs']);

gulp.task('docs.example', function () {
	var dest = path.src + '_';
	return gulp.src(path.src + '/**/*.php')
		//.pipe(g.changed(dest))
		.pipe(g.intercept(function (file) {
			var dss = require('dss');

			dss.parser('example', function (i, line, block, file) {
				// find the next instance of a parser (if there is one based on the @ symbol)
				// in order to isolate the current multi-line parser
				var nextParserIndex = block.indexOf('* @', i + 1);
				var bodyLength = nextParserIndex > -1 ? nextParserIndex - i : block.length;
				var body = block.split('').splice(i, bodyLength).join('');
				var body = (function (body) {
					var r = [],
					lines = body.split('\n');
					lines.forEach(function (line) {
						var pattern = '* ',
						index = line.indexOf(pattern);

						if (index > 0 && index < 10)
							line = line.split('').splice((index + pattern.length), line.length).join('');

						if (lines.length <= 2) line = dss.trim(line);
						if (line && line != '@example') r.push(line);
					});
					return r.join('\n');
				})(body);
				
			 	shell('kss-node --config ' + paths.kssConf, function(error, stdout, stderr) {
			 		if (error !== null) console.log('' + error);
			 		else console.log(stdout);
			 	});

				return body;
			});

			dss.parse(file.contents.toString(), {}, function (data) {
				file.contents = new Buffer(JSON.stringify(data, null, "\t"));
			});
			return file;
		}))
		.pipe(gulp.dest(dest));
});

gulp.task('docs', ['docs.clean'], function (done) {
	shell('apigen generate -s ' + path.src + ' -d ' + path.docs,
	function (error, stdout, stderr) {
		if (error !== null) console.log('' + error);
		else console.log(stdout);
		done();
	});
});

gulp.task('docs.clean', function (done) {
	return del([path.docs + '/**/*'], done);
});

gulp.task('docs.deploy', ['docs'], function () {
	return gulp.src(path.docs + '/**/*')
		.pipe(g.ghPages());
});

gulp.task('reload', function (done) {
	bsync.reload();
	done();
});

gulp.task('watch', ['docs'], function () {
	bsync.init({
		server: {
			baseDir: path.docs
		}
	});

	gulp.watch([
		path.src + '/**/*.php',
		path.pkg,
		path.docsConf
	], function (event) {
		series('docs', 'reload'); // Run tasks synchronously
	});
});
