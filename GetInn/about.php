<?php include('header.php');
ini_set('display_errors', 1);
	$qry2=mysqli_query($con,"select * from tbl_movie where movie_id='".$_GET['id']."'");
    $movie=mysqli_fetch_array($qry2);
	?>

  <main id="main">

    <!-- ======= Breadcrumbs Section ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Movie Detail</h2>
          <ol>
            <li><a href="index.html">Home</a></li>
            <li><?php echo $movie['movie_name']; ?></li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs Section -->

    <?php
        $api_key = '29ebc7b6';
        $imdb_id = $movie['imdb'];
        if(empty($api_key))
        {
            echo 'Get API key from http://www.omdbapi.com/';
            die();
        }
        $data = json_decode(file_get_contents('http://www.omdbapi.com/?i='.$imdb_id.'&plot=full&apikey='.$api_key.''), true);
    ?>
      
      <div class="container">
          <div class="row">
              
              <div class="col-lg-5 box">
                  <?php echo '<img src="'.$data['Poster'].'" alt="'.$data['Plot'].'" class="movie-img"/>'; ?>
              </div>
              
              <div class="col-lg-7 box">
                <h3><?php echo $data['Title']; ?></h3>
                <ul class="uk-subnav uk-subnav-line">
                    <li class="single-rating"><span class="fa fa-star" aria-hidden="true"></span><?php echo $data['imdbRating']; ?><span>/10 (<?php echo $data['imdbVotes']; ?> Votes)</span></li>
                    <li><?php echo $data['Runtime']; ?></li>
                    <li><?php echo $data['Year']; ?></li>
                </ul>
                  <hr>
                  <p><?php echo $data['Plot']; ?></p>
                  
                <dl class="uk-description-list-horizontal uk-margin-top">
                    <dt>Actors</dt>
                    <dd><?php echo $data['Actors']; ?></dd>
                    <dt>Director</dt>
                    <dd><?php echo $data['Director']; ?></dd>
                    <dt>Writer</dt>
                    <dd><?php echo $data['Writer']; ?></dd>
                    <dt>Genres</dt>
                    <dd><?php echo $data['Genre']; ?></dd>
                    <dt>Awards</dt>
                    <dd><?php echo $data['Awards']; ?></dd>
                    <dt>Language</dt>
                    <dd><?php echo $data['Language']; ?></dd>
                </dl>
                  
                  
                    <?php $s=mysqli_query($con,"select DISTINCT theatre_id from tbl_shows where movie_id='".$movie['movie_id']."'");
							if(mysqli_num_rows($s))
							{?>
							<table class="table table-hover table-bordered text-center" style="margin-top:50px;">
							<?php
								
								while($shw=mysqli_fetch_array($s))
								{
									$t=mysqli_query($con,"select * from tbl_theatre where id='".$shw['theatre_id']."'");
									$theatre=mysqli_fetch_array($t);
									?>
									<tr>
										<td>
											<?php echo $theatre['name'].", ".$theatre['place'];?>
										</td>
										<td>
											<?php $tr=mysqli_query($con,"select * from tbl_shows where movie_id='".$movie['movie_id']."' and theatre_id='".$shw['theatre_id']."'");
											while($shh=mysqli_fetch_array($tr))
											{
												$ttm=mysqli_query($con,"select  * from tbl_show_time where st_id='".$shh['st_id']."'");
												$ttme=mysqli_fetch_array($ttm);
												
												?>
												
												<a href="check_login.php?show=<?php echo $shh['s_id'];?>&movie=<?php echo $shh['movie_id'];?>&theatre=<?php echo $shw['theatre_id'];?>"><button class="btn btn-default"><?php echo date('h:i A',strtotime($ttme['start_time']));?></button></a>
												<?php
											}
											?>
										</td>
									</tr>
									<?php
								}
							?>
						</table>
							<?php
							}
						
							else
							{
								?>
								<h3>No Show Available</h3>
								<?php
							}
							?>
              </div>
              <iframe width="100%" height="600px" style="margin-top: 100px;
    border: none;" src="<?php echo $movie['video_url']; ?>"></iframe>
        </div>
      </div>
</main>

<?php include('footer.php');?>