<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

<style>
	.card_container {
		border: 1px #000 solid;
		padding: 0;
		margin: 0 0 20px 0;
		display: block;
		clear: both;
		height: 100px;
	}
	.card_avatar {
		float: left;
		width: 150px;
		height: 100px;
		background-color: aliceblue;
		padding: 0;
		margin: 0;
	}
	.card_avatar img {
		max-height: 100%;
		
	}
	.card_play_btn {
		float: left;
		display: flex;
		justify-content: center; 
		align-items: center;
		width: 70px;
		height: 100px;
		font-size: 35px; 
		color: Dodgerblue;
	}
	.card_details {
		float:left;
		display: flex;
/*		justify-content: center; */
		align-items: center;
		height: 100px;
/*		width: 55%; */
		background-color: cornsilk;
	}
	.talent_name {
		font-size: 1.5em;
		background-color: aqua;
	}
	.talent_details {
		background-color: aquamarine;
	}
	.card_actions {
/*		float: right; */
		position: absolute;
		right: 0;
		width:75px;
		height: 100px;
		background-color: azure;
	}
	.download_btn {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 75px;
		height: 50px;
		font-size: 25px; 
		color: Dodgerblue;
	}
	.order_btn {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 75px;
		height: 50px;
		font-size: 25px; 
		color: Dodgerblue;
	}
</style>


<div class="card_container">
	<div class="card_avatar"><img src="/wp-content/uploads/2018/12/fav/ABE-150x150.jpeg"></div>
	<div class="card_play_btn"><i class="far fa-play-circle"></i></div>
	<div class="card_details">
		<p><span class="talent_name">Talent Name</span><br>
		<span class="talent_details">Mature, upbeat, blah</span></p>
	</div>
	<div class="card_actions">
		<div class="download_btn"><i class="fas fa-download"></i></div>
		<div class="order_btn"><i class="fas fa-shopping-cart"></i></div>
	</div>
</div>




<div class="card_container">
	<div class="card_avatar"><img src="/wp-content/uploads/2018/12/fav/ABE-150x150.jpeg"></div>
	<div class="card_play_btn"><i class="far fa-play-circle"></i></div>
	<div class="card_details">
		<p><span class="talent_name">Talent Name</span><br>
		<span class="talent_details">Mature, upbeat, blah blah blah</span></p>
	</div>
	<div class="card_actions">
		<div class="download_btn"><i class="fas fa-download"></i></div>
		<div class="order_btn"><i class="fas fa-shopping-cart"></i></div>
	</div>
</div>
