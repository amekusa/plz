var path = {
	pkg: 'package.json',
	src: 'src',
	docs: 'docs',
	docsConf: 'apigen.neon'
};

var pkg = require('./' + path.pkg);
var gulp = require('gulp');
var g = require('gulp-load-plugins')();
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

gulp.task('docs.deploy', ['docs'], function (done) {
	var remote = 'origin';
	var branch = 'gh-pages';
	g.git.exec({args: 'subtree push --prefix ' + path.docs + ' ' + remote + ' ' + branch},
	function (error, stdout) {
		if (error !== null) console.log('' + error);
		else console.log(stdout);
		done();
	});
});

gulp.task('watch', function () {
	gulp.watch([
		path.src + '/**/*.php',
		path.pkg,
		path.docsConf
	], ['docs']);
});
