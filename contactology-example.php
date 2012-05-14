 
<?php

//Require the Contactology base class
require "class.Contactology.php";

//Create a new Contactology object - don't forget to replace "YOURAPIKEY" with your API key
$c = new Contactology( "YOURAPIKEY" );

//First, let's create our list.&nbsp; We want to be able to use Contactology's EasyCast feature with this list,
//&nbsp; so we specify our EasyCast name via the optionalParameters argument
$listId = $c->List_Add_Public( "FakeCon 2010 List", array( "easycastName" => "fakecon2010" ) );

//Here are some contacts we want to add to our list - up to 1000 contacts can be added at a time in some calls
//Note that we are setting the contacts' First and Last names during the initial setup for that contact,
// you can actually set the value for any custom field, including ones you create with the CustomField_Add calls
// available in the REST API
$contacts = array(
&nbsp;&nbsp;&nbsp; array( "email" => "steve@domain.com", "first_name" => "Steve", "last_name" => "Jorbs" ),
&nbsp;&nbsp;&nbsp; array( "email" => "william@domain.com", "first_name" => "Billy", "last_name" => "Clockwell" )
);

//Now we add the contacts to the list we created - we have to provide where we got the contacts for reference later
$results = $c->List_Import_Contacts( $listId, "FakeCon 2010 Booth signup", $contacts );

//Let's set up some values for our campaign - right now, we're only sending this to one list, but
//&nbsp; we could send to several lists, groups or contacts found via saved searches.&nbsp; If we wanted to, we could
//&nbsp; also create an AdHoc campaign and send to a group of e-mail addresses without creating a list first,
//&nbsp; but since we got these contacts all at once, it's good to have them organized in a list
$recipients = array( "list" => $listId );

//Let's set up the HTML content for our e-mail
$content = array();
$content['html'] = <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>E-mail</title>
</head>
<body>
<img src="http://domain.com/logo.gif">
Thanks for signing up at our FakeCon booth - we'll be sending you
information in the near future.
</body>
</html>
HTML;

//Now let's create our campaign - we give it the recipients, a name, a subject line, the e-mail the campaign
// is sent on behalf of, that person's name, the content of the e-mail (we're giving HTML only this time), and
// some optionalParameters.&nbsp; We want Contactology to track replies to this message, and include a "View In Browser"
// link.
$campaignId = $c->Campaign_Create_Standard( $recipients, "FakeCon 2010 Thanks", "Thanks for seeing us at FakeCon",
&nbsp;&nbsp;&nbsp; "mike@domain.com", "Mike Domain", $content, array( "trackReplies" => true, "viewInBrowser" => true ) );

//At this point, we don't have to send the campaign, it is in "Draft" mode and waiting to be sent - if we know
// we want to add more people to the list first, we could.&nbsp; But for this example, we're just going to send right away.
$c->Campaign_Send( $campaignId );

//And off it goes - that's all for this example!
