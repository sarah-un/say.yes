# Creating a phrase generator
### Wolfgang Gruel

The following file describes how to create a phrase generator. It describes creating the service step-by-step. If you want to build on the state that we discussed during our class, you can download the code on Github: https://github.com/wgruel/i219s

Make sure, you put the files into a subfolder of your document root (see "Setup system" section). There will an .sql files in that folder - this contains the current database. You can open that file with a normal texteditor (e.g., ATOM or Sublime) and copy the text to the SQL-Tab in PhpMyAdmin AFTER having selected a database (see Setup database section).

### Disclaimer
We want to create PHP-Files that access our database, so we can view, change and delete phrases and users from the browser. In order to learn the concepts, we do this in a simplistic way without considering modern architecture patterns...

If you have any questions about PHP functions, the documentation on www.php.net is a very helpful resource. 

## Setup system

Install Xampp on your machine (https://www.apachefriends.org/de/download.html).
The directory that the webserver works with is called "htdocs" (= document root). It is a subdirectory of your Xampp-Installation folder (on Mac "/Applications/XAMPP/htdocs", on Windows ususally something like "C:\xampp\htdocs")

## Basic steps to achieve the goal (Overview)

We will perform the following steps
- Step 1: Send data to server via form (using <form>-tag + <submit>-buttons, if submitted via GET, we can see the transferred data in the URL)
- Step 2: Recieve data on server via PHP (using $_REQUEST, $_GET, or $_POST arrays) and process it
- Step 3: Store data on server (via file-system first, then using a database)
- Step 4: Dynamicalls create a response with PHP that might contain data that has just been stored

## Create the input interface

The first thing, we want to do, is to create an input site.

We will need a headline:

```
<h1>I say YES! to ...</h1>
```

To submit that information, we need a form. As a first step, we will just send the information to the page itself (no action will be set) and will use GET as a method to transmit the data.

```
<form method="get">
 <!-- form content goes here -->
</form>
```
Without form-tag, there is no submission of data. The form-data can be sent via GET (data visible in URL) or via POST (data not visible in URL). 

As input, we will use two select-fields - every phrase-element will be put into an option-tag. Each of the selects needs to get a unique name (e.g. "phrase_01").

```
    <select class="custom-select" name="phrase_01">
        <option selected>Open this select menu</option>
        <option value="learning">learning</option>
        <option value="exploring">exploring</option>
        <option value="finding">finding</option>
        <option value="enjoying">enjoying</option>
    </select>
```

If we want to use special characters as values, we need to URL-encode them (e.g., replace spaces with %20).

Then, we will need a button in order to submit all that stuff:
```
    <button type="submit" class="btn btn-default" name="btn-save" value="1">Say YES!</button>
```

If we load the page in our browser now (in localhost context), we select several options, now. If we press the button, the address-line of our browser changes - we submit the entered data to the server. Nice.


## Processing the input

We want to process this input now. Therefore, we put some PHP to the top of the page. This PHP is supposed to check if the button was pressed. To do that, we process the information that was delivered via the $_GET array (the form data should be stored here...).

```
<?php
  if(isset($_GET['btn-save'])){
    // here, we will put the save-operations...
    // but we can just output some stuff that was sent to our page...
    echo $_GET['phrase1'];
  }
?>
```

``` isset()``` checks if a variable is set or not. 
``` echo $_GET['phrase1'] ``` writes the content that has been transmitted to the page and that is stored in the GET-variables 'phrase1' field to the screen. 


## Save the input to a file
We can simply store all the information to a file now.
We define a variable called $filename and a variable called $text - this variable is supposed to contain all the text and contains of the two elements that are delivered via the $_GET parameter.  
```
    $filename = "file.txt";
    $text = $_GET['phrase_01'] . " " . $_GET['phrase_02'];
    file_put_contents($filename, $text);

```

``` file_put_contents($filename, $text) ``` stores data that is stored in variable $text in a file called $filename. This file is located in the same folder as the php-file.

If you submit the form now, you should be able to see a new file that contains the information that you just selected. Check if the file was written and open it with a text editor. It should contain all the submitted information, now.

In case, you encounter problems, you might want to check the permissions (Right-click the folder and check if the webserver has write-access...).

Unfortunately, the information that we want to write is URL-encoded (contains strange %20s). We want to store the information in a different style, so we have to perform an URL-decode operation:

```
$text = urldecode($text);
```

## Reading the phrases from a file

We will create a new file to read all the phrases: phrasesList.php.

We add the following code to the top of the file in order to read the contents of the file:

```
<?php
  $filename = "file.txt";
  $text = file_get_contents($filename);
?>
```

This piece of code assumes that the file called "file.txt" exists. If this is not the case, we'll get an error. 

In the HTML-Part, we just echo the content of $text in order to put the file content to the right place:

<?php echo $text ?>

## Adding more phrases.

In case we want to add new phrases, we can just change the way PHP stores data to the file. Right now, it overwrites the file, whenever we submit the form. By adding the parameter FILE_APPEND to the function call in phrases_add.php, new content will be added at the end of the file $filename.

```
    file_put_contents($filename, $text, FILE_APPEND);
```

Check it out...

If you open the phrase_list.php file, you will notice that the whole content is written in one line. This is probably not what we want. In order to create multiple lines, we first add a line end to each of the new phrases we enter. This is done by adding `"\n"` to the string we want to put in the file. In file.txt, we already see the difference - but not in the HTML-file. There are multiple ways to fix this. The easiest is to just put a `<pre>` tag around the text. It looks not so beautiful.  

## Reading the content line by line

What we want to do, is to read the content line by line. In phrase_list.php, we replace `file_put_contents()` with `file()`.

In the head, we put:

```
  $statements = file($filename, FILE_IGNORE_NEW_LINES);
```

``` file() ``` returns the contents of the file $filename as an array. Each line is stored in a separate array-element ($statement[0] => "Line 1", $statement[1] => "Line 2"). 

In the body, we need to loop through the statements-array, now:
```
    <?php
    foreach ($statements as $stmt){
        echo "<p>". $stmt . "</p>";
    }
    ?>
```

By using the foreach statement we loop through the $statements array. On each iteration, the value of the current element of $statement is assigned to $stmt and the internal array pointer is advanced by one (so on the next iteration, you'll be looking at the next element). In the loop, we can access the current array element by using the $stmt variable.

## Create your first config file

We want to put the name of the file that we use to store the information into a centralized file. What we will do, is create a new file called `config.php`. We will use that file in every other file in order to store centralized information. For now, we only put the following information into that file:

```
<?php
  // name of the file that we store data to
  // we want to use this information in different files 
  $filename = "file.txt";
?>

```

We replace the name that determines the filename in our other files and import the config-file:

```
include('config.php');
```

By putting that line to the very top, we make sure the contents is read (and thus, $filename is defined) before we execute any other code. 


