# Plugin Build Workflow

Description: An automation process that handles tasks that you would normally do manually

Note: This assumes that you have a local installation of the project such that it can be run from http://localhost/<installation_folder> 

## Getting started

#### 1. Install nodejs globally:

Download the Node.js pre-built installer for mac from:   

<http://nodejs.org/dist/v0.12.4/node-v0.12.4.pkg>

#### 2. Install gulp globally:

```
$ sudo npm install --global gulp
```

#### 3. Install project dependencies from package.json:

```
$ sudo npm install
```

#### 4. Enter the location of your local wordpress installtion in gulpfile.js:

1. Open the gulpfile.js in your favourite editor (SublimeText, Atom, etc)
2. Find the chunk of code similar to the following:

```
  browserSync (files, {

      proxy: "localhost/wordpress",
```
and replace 'localhost/wordpress' with the location of you local wordpress installation.

#### 4. Run gulp:

```
$ gulp
```

This will open your default browser with a link to your wordpress installtion. Any changes you make to any of the ".php"" files will be recognised and the browser will be refreshed.