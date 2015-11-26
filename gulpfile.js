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

gulp.task('watch', function () {
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
