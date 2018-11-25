<?php
/**
 * Created by PhpStorm.
 * User: Tayyab Ejaz
 * Date: 10/08/2018
 * Time: 4:22 PM
 */
?>
<style>
	#div_add
	{
		width: 100%;
		height: 300px;
		background: gray;
	}

	#h1_add
	{
		font-size: larger;
		font-weight: bolder;
		align-self: center;
	}

	#button_add
	{
		border-radius: 5px;
		background: transparent;
		font-weight: bold;
		font-size: medium;
		color: #048BA8;
	}
</style>

<div id="div_add">
	<h1 id="h1_add"> Your Ads Placed Here </h1>
</div>

<form action="/wp-content/plugins/NewPlugin/assets/buy_ads.php">
	<select id="token" name="token" >
		<option value="5"> 5 tokens for $5 </option>
		<option value="10"> 10 tokens for $10 </option>
		<option value="15"> 15 token for $15 </option>
	</select>

	<button id="button_add" value=""> Buy Tokens </button>
</form>