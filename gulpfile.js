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
var tmp = require('tmp');

gulp.task('default', ['build']);
gulp.task('build', ['docs']);

gulp.task('docs.example', function () {
	var dest = path.src + '_';
	return gulp.src(path.src + '/**/*.php')
		//.pipe(g.changed(dest))
		.pipe(g.intercept(function (file) {
			var dss = require('dss');
			
			dss.detector(function (line) {
				if (typeof line !== 'string') return false;
				var reference = line.split("\n\n").pop();
				return !!reference.match(/.*@/);
			});
			
			dss.parser('example', function (i, line, block, file) {
				var codeStart = block.indexOf('* ```php\n', i + 1) + 10;
				var codeEnd = block.indexOf('* ```\n', codeStart + 1);
				var codeLength = codeEnd > -1 ? codeEnd - codeStart : block.length;
				var code = block.split('').splice(codeStart, codeLength).join('');
				var code = (function (code) {
					var r = [],
					lines = code.split('\n');
					lines.forEach(function (line) {
						var pattern = '* ',
						index = line.indexOf(pattern);

						if (index > 0 && index < 10)
							line = line.split('').splice((index + pattern.length), line.length).join('');

						if (lines.length <= 2) line = dss.trim(line);
						if (line && line != '@example') r.push(line);
					});
					return r.join('\n');
				})(code);
				
				/*
				tmp.file(function (error, path, fd, cleanup) {
					if (error) throw error;
					fs.writeSync(fd, '<?php namespace amekusa\\plz; require ""; ' + code);
					shell('php ' + path, function (error, stdout, stderr) {
						if (error) console.log(error);
						else console.log(stdout);
					});
					fs.closeSync(fd);
				});
				*/
				
				fs.writeFileSync('tmp.php', '<?php namespace amekusa\\plz;\nrequire "vendor/autoload.php";\n' + code);
				shell('php ' + 'tmp.php', function (error, stdout, stderr) {
					if (error) console.log(error);
					else console.log(stdout);
				});

				return code;
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
