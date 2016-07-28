![alt tag](https://raw.githubusercontent.com/doubtingreality/HumbleGraph/master/screenshot.png)

# HumbleGraph
HumbleGraph is a statistical tool for keeping track of Humble Bundle prices.

---

## Setup
### 1. Compile SCSS
Compile the main.scss with your own Sass compiler.
Here's a Ruby Sass example:
```
sass --watch main.scss:../css/main.css --style compressed --sourcemap=none
```

### 2. Setup nginx or .htaccess redirect
You'll need to setup a redirect for correct URL recognition.
The nginx redirect I use is:
```
location / {
	try_files $uri $uri/ =404;

	# Set variable for if file exists
	set $exists 0;

	# Two if statements (one for files, one for directories) because nginx doesn't allow AND operator
	if (-f $request_filename) {
		set $exists 1;
	}

	if (-d $request_filename) {
		set $exists 1;
	}

	# Redirects here; first the deeper URLs
	if ($exists = 0) {
		rewrite ^/humblegraph/(.+) /humblegraph/?bundle_name=$1 last;
		rewrite ^/(.+) /?query=$1 last;
	}
}
```