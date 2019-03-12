<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

<style>
	.talents_table {
		border-collapse: collapse;
		padding: 0 !important;
		margin: 0 !important;
	}
	.talents_table tr, .talents_table td {
		padding: 0 !important;
		margin: 0 !important;
	} 
	.avatar {
		width: 100px;
	}
	.play_btn {
		width: 370px;
		font-size: 35px; 
		color: Dodgerblue;
		text-align: center;
	}
	.download_btn {
		width: 70px;
		font-size: 35px; 
		color: slategrey;
		text-align: center;
	}
	.talent_name {
		font-size: 1.5em;
		margin: 0 0 0 10px;
	}
	.talent_details {
		margin: 0 0 0 10px;
	}
	.sc_player_container1 .myButton_play {
		
	}
	.mejs-container, .mejs-embed, .mejs-embed body, .mejs-container .mejs-controls {
    	background: Dodgerblue !important;
		border-radius: 30px;
		width: 250px !important;
	}
</style>


<table class="talents_table">
<?php
 for ($i = 0; $i < 10; $i++) {	
?>
		<tr>
		<td class="avatar"><img src="/wp-content/uploads/2018/12/fav/ABE-150x150.jpeg" width="100" height="100"></td>
		<td><p><span class="talent_name">Talent Name</span><br>
		<span class="talent_details">Mature, upbeat, blah blah blah</span></p></td>
		<td class="actions download_btn"><i class="fas fa-download"></i></td>
		<td class="actions download_btn"><i class="fas fa-shopping-cart"></i></td>
		<td class="play_btn"><?php echo do_shortcode( '[sc_embed_player_template1 fileurl="https://voice.livingformusicgroup.com/wp-content/uploads/2018/12/fav/mpthreetest2.mp3"]' ); ?></td>
	</tr>
<?php } ?>

</table>