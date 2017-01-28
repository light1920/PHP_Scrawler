<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> <!-->To add additional functionality and event handling.<-->
<script>
//Function to open up a div box with the contents of the headlines of a link which is hovered upon.
$(document).ready(function(){
	//when the cursor is on the link, function to open the div box and load the content into it.
    $(".link").mouseenter(function()
	{
        var href = $(this).attr('href'); //getting the href attribute from the link which is hovered upon.
		$("#output").fadeIn(); //fadeIn the Div Box
		$("#close").fadeIn(); //fadeIn the close button
		$("#output").html("<object data='" + href + "' style='height:100%; width:100%;'></object>"); //load the div box with object loading the external link of the headline.
		
    });
	//function to close the div box.
	$("#close").click(function()
	{
		$("#output").html('');
		$("#output").fadeOut();	
		$("#close").fadeOut();	
    });
});
</script>

<style>
#close
{
	margin-top:-20px;
	z-index:10;
	color: white;
	background-color: black;
}

#close:hover
{
	cursor: pointer;
}
</style>
</head>
<body>
<?php

//creating two div box to show the news content and a close button.
echo "<div id='close' style='position:fixed; margin-left:40%; display:none;'>Close";
echo "</div>";
echo "<div id='output' style='position:fixed; height:95vh; width:120vh; margin-left:40%; box-shadow: 5px 5px 15px black; overflow:scroll; display:none;'>Loading..Please Wait";
echo "</div>";

//div box which will hold all the headlines
echo "<div id='main' style='height:auto; width:auto;'>";
echo "<h1 style='background-color:black; color:white; font-family:Verdana, Geneva, sans-serif;'>Latest News About Trump</h1>";

//getting the target url from where the data will be extracted
$target_url = 'http://edition.cnn.com/specials/last-50-stories';

//loading the Simple HTML DOM file for parsing the web content
include_once('simple_html_dom.php');

//creating a DOM object
$html = new simple_html_dom();

//Loading the target URl  into the html dom object which will contain all the contents of the url provided. Now we have to do parsing to extract the desired data.
$html->load_file($target_url);

//All the headines are showed on the webpage using <li> tags. First start extracting the <li> tag to get the headlines of all the latest news.
//Start looping all the <li> tags as you dont know how many headlines are there. foreach function will terminates when there is no further <li> tag is left.
//The cnn page contains only 50 latest news so we dont have to worry about the limit.
//In case we need to limit further our search, use a counter to get the number of news obtained. eg $i++ should be less than or equal to 15.
foreach($html->find('li') as $li)
{
	echo "<div class='content'>";
	//checking if the headline containes Trump keyword or not. If yes then display the headline or else skip it.
	if ((strpos($li, 'trump') !== false) or (strpos($li, 'Trump"s') !== false))
	{
		//Trump keyword found, now extract the heading of the news. CNN uses <h3> tag to display all the headlines of their news.
		foreach($li->find('h3') as $h3)
		{
			//once <h3> is found, display it.
			echo "<h3>" . $h3 . "</h3>";
			//break is used because we are only interested in the headline content. The DOM object also contains nested DOM objects and arrays which are unimportant in our case.
			break;
		}
		//get the href of the image of the headline used as a thumbnail.
		foreach($li->find('img') as $img)
		{
			//getAttribute is the function of DOM to read the content of an attribute. we have stored the link to a variable.
			$i = $img->getAttribute('data-src-mini');
			//display the link using <img> tag in our PHP webpage.
			echo "<img src='". $i ."'>";
			//again break is used as there are unneessary DOM objects and nested arrays are present
			break;
		}
		//extract the link to the headlines by finding the <a> tag in <li>
		foreach($li->find('a') as $link)
		{
			//the extracted link will contain url of the folder structure which CNN is maintaing to save their data.
			//By default, if you are running Apache webserver, your localhost will throw an error as object not found as you dont have the information saved in your computer.
			//so after extracting the url, u need to append it to proper url which points to CNN online link.
			$newlink = "edition.cnn.com";
			$link = $link->href;
			$newlink .=$link;
			//echo $newlink;
			//after everything is done, disply it with the rest of the content.
			echo "<a class='link' href='http://" . $newlink . "' target='_blank'>Read the full story here</a>";
			break;
		}
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
	}
	echo "</div>";
}
echo "</div>";
//
//-------------NOTE: The links of each of the headlines are displayed with the real content from CNN. The PHP page sorts the lates news of Trump and displayes it.
//------------------In case the news should also be parsed by the scrapper, we can navigate to the link and load its content and have the desired information in the same way
//-------------------we did above and disply it over our PHP page.
?>
</body>
</html>