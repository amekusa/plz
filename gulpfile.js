var paths = {
	pkg: 'package.json',
	src: 'src',
	docs: 'docs',
	docsConf: 'apigen.neon',
	tmp: '.tmp'
};

var pkg = require('./' + paths.pkg);
var gulp = require('gulp');
var g = require('gulp-load-plugins')();
var series = require('run-sequence'); // TODO Replace with gulp.series on Gulp 4.0 release
var bsync = require('browser-sync');
var shell = require('child_process').exec;
var del = require('del');
var util = require('util');
var fs = require('fs');
var tmp = require('tmp');
var transform = require('vinyl-transform');
var map = require('map-stream');

gulp.task('default', ['build']);
gulp.task('build', ['docs']);

gulp.task('tmp', function (done) {
	try {
		fs.mkdirSync(paths.tmp);
	} catch (e) {
		if (e.code != 'EEXIST') throw e;
	}
	return del([paths.tmp + '/**/*'], done);
});

gulp.task('docs.example', ['tmp'], function () {
	var dest = paths.src + '_';

	var parser = transform(function () {
		return map(function (chunk, next) {
			var content = chunk.toString();
			var lines = content.split('\n');
			var newLines = [];
			var contexts = [{name: 'root'}];

			for (var i = 0; i < lines.length; i++) {
				newLines.push(lines[i]);
				var line = lines[i].trim();
				var context = contexts[contexts.length - 1];

				switch (context.name) {
				case 'root':
					if (line.match(/^\/\*{2}$/))
						contexts.push({name: 'doc'}); // Doc starts at "/**"
					continue;
				case 'doc': // In a doc block
					if (line.match(/^\*\//)) contexts.pop(); // Ends at "*/"
					else if (line.match(/^\* @example/))
						contexts.push({name: 'example'}); // Example starts at "* @example"
					continue;
				case 'example': // In a example block
					if (line.match(/^\* @\w+/)) contexts.pop(); // Ends at "* @anything"
					else if (line.match(/^\*\//)) {
						contexts.pop(); // Ends at "*/"
						i--;
					} else {
						var matches = lines[i].match(/^(\s* \* )```php$/);
						if (matches) {
							contexts.push({
								name: 'php',
								base: matches[1],
								lines: []
							});
						}
					}
					continue;
				case 'php':
					if (line.match(/^\* ```$/)) { // Ends at "```"
						var code = context.lines.join('\n');
						// TODO Evaluate code
						contexts.pop();
					} else context.lines.push(lines[i].substr(context.base.length));
					continue;
				}
			}
			return next(null, content);
		});
	});

	return gulp.src(paths.src + '/**/*.php')
		//.pipe(g.changed(dest))
		.pipe(parser)
		/*
		.pipe(g.intercept(function (src) {
			var dss = require('dss');

			dss.detector(function (line) {
				if (typeof line !== 'string') return false;
				var reference = line.split('\n\n').pop();
				return !!reference.match(/^\s+\* @\w+\s+/);
			});

			dss.parser('example', function (i, line, block, file) {
				// TODO Test
				console.log('i: ' + i);
				console.log('block: ' + block);

				var open = '* ```php\n';
				var close = '* ```\n';
				var from = block.indexOf(open, i + 1) + open.length;
				var to = block.indexOf(close, from);
				var length = to > -1 ? to - from : block.length;
				var code = block.split('').splice(from, length).join('');
				code = (function (code) {
					var r = [],
					lines = code.split('\n');
					lines.forEach(function (line) {
						var pattern = '* ';
						var index = line.indexOf(pattern);
						if (index > 0) line = line.split('').splice((index + pattern.length), line.length).join('');
						if (lines.length <= 2) line = dss.trim(line);
						if (line) r.push(line);
					});
					return r.join('\n');
				})(code);

				var open = '* ```html\n';
				var from = block.indexOf(open, to + close.length);
				var to = block.indexOf(close, from);
				var length = to > -1 ? to - from : block.length;
				var result = block.split('').splice(from, length).join('');

				var tmp = paths.tmp + '/example.php';
				fs.writeFileSync(tmp, '<?php namespace amekusa\\plz;require "vendor/autoload.php";' + code);
				shell('php ' + tmp, function (error, stdout, stderr) {
					if (error) return console.log(error);
					console.log(stdout);
				});

				return code;
			});

			dss.parse(src.contents.toString(), {}, function (data) {
				//src.contents = new Buffer(JSON.stringify(data, null, "\t"));
			});

			return src;
		}))
		*/
		.pipe(gulp.dest(dest));
});

gulp.task('docs', ['docs.clean'], function (done) {
	shell('apigen generate -s ' + paths.src + ' -d ' + paths.docs, function (error, stdout, stderr) {
		if (error !== null) console.log('' + error);
		else console.log(stdout);
		done();
	});
});

gulp.task('docs.clean', function (done) {
	return del([paths.docs + '/**/*'], done);
});

gulp.task('docs.deploy', ['docs'], function () {
	return gulp.src(paths.docs + '/**/*')
		.pipe(g.ghPages());
});

gulp.task('reload', function (done) {
	bsync.reload();
	done();
});

gulp.task('watch', ['docs'], function () {
	bsync.init({
		server: {
			baseDir: paths.docs
		}
	});

	gulp.watch([
		paths.src + '/**/*.php',
		paths.pkg,
		paths.docsConf
	], function (event) {
		series('docs', 'reload'); // Run tasks synchronously
	});
});
