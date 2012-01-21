<div id="comments">
<h2 class="crossline"><span>Comments</span></h2><br />
<?php
if (isset($uvanetid)) {
	?>
<form method="post" action="_self" name="comments">
	<h3>Titel</h3>
	<input type="text"  size="63" />
<h3>Body</h3>
	<textarea cols="50" rows="10" ></textarea>
	<input type="submit" class="submit" name="submit" value="Plaats comment" />
</form>
<div id="stylized" class="myform">
<form id="form" name="form" method="post" action="index.html">
<h1>Sign-up form</h1>
<p>This is the basic look of my form without table</p>

<label>Name
<span class="small">Add your name</span>
</label>
<input type="text" name="name" id="name" />

<label>Email
<span class="small">Add a valid address</span>
</label>
<input type="text" name="email" id="email" />

<label>Password
<span class="small">Min. size 6 chars</span>
</label>
<input type="text" name="password" id="password" />

<button type="submit">Sign-up</button>
<div class="spacer"></div>

</form>
</div>

	<?php
} else {
	echo "Je moet ingelogd als student zijn om comments van andere studenten te zien en om zelf comments te plaatsen";
}
?>
</div>