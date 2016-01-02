var paths = {
	pkg: 'package.json',
	src: 'src',
	docs: 'docs',
	docsConf: 'apigen.neon',
	build: 'build',
	buildSrc: 'build/src'
};

var pkg = require('./' + paths.pkg);
var gulp = require('gulp');
var g = require('gulp-load-plugins')();
var series = require('run-sequence'); // TODO Replace with gulp.series on Gulp 4.0 release
var bsync = require('browser-sync');
var sh = require('child_process');
var del = require('del');
var util = require('util');
var fs = require('fs');
var tmp = require('tmp');
var transform = require('vinyl-transform');
var map = require('map-stream');
var emoji = require('node-emoji');

gulp.task('default', ['build']);
gulp.task('build', ['docs']);

gulp.task('build:src', function () {
	var dest = paths.buildSrc;
	return gulp.src(paths.src + '/**/*.php')
		.pipe(g.changed(dest))
		.pipe(transform(function () {
			return map(function (chunk, next) {
				var lines = chunk.toString().split(/\n/);
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
						if (line.match(/^\* @example/)); // Does nothing at "* @example"
						else if (line.match(/^\* @\w+/)) contexts.pop(); // Ends at "* @whatever"
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
							var header = '<?php namespace amekusa\\plz;require "' + __dirname + '/vendor/autoload.php";';
							var executable = tmp.fileSync({prefix: 'plz-tmp', postfix: '.php'});
							fs.writeSync(executable.fd, header + code);
							var result = sh.execSync('php ' + executable.name).toString().trim(); // Evaluate the code
							if (result) {
								newLines = newLines.concat(('```php\n' + result + '\n```').split(/\n/)
									.map(function (line) { // Process every lines
										return context.base + line;
									}));
							}
							contexts.pop();
						} else context.lines.push(lines[i].substr(context.base.length));
						continue;
					}
				}
				return next(null, newLines.join('\n'));
			});
		}))
		.pipe(gulp.dest(dest));
});

gulp.task('build:readme', function () {
	var dest = paths.build;
	return gulp.src('README.md')
		.pipe(g.changed(dest, {extension: '.html'}))
		.pipe(transform(function () {
			return map(function (chunk, next) {
				return next(null, emoji.emojify(chunk.toString()));
			});
		}))
		.pipe(g.marked())
		.pipe(g.rename({extname: '.html'}))
		.pipe(gulp.dest(dest));
});

gulp.task('docs', ['docs:index']);

gulp.task('docs:index', ['docs:api', 'build:readme'], function () {
	return gulp.src(paths.docs + '/index.html')
		.pipe(g.dom(function () {
            this.querySelector('#content').innerHTML = fs.readFileSync(paths.build + '/README.html');
			return this;
        }))
		.pipe(gulp.dest(paths.docs));
});

gulp.task('docs:api', ['docs:clean', 'build:src'], function (done) {
	sh.exec('apigen generate -s ' + paths.buildSrc + ' -d ' + paths.docs, function (error, stdout, stderr) {
		if (error !== null) console.log('' + error);
		else console.log(stdout);
		done();
	});
});

gulp.task('docs:clean', function (done) {
	return del([paths.docs + '/**/*'], done);
});

gulp.task('docs:deploy', ['docs'], function () {
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
