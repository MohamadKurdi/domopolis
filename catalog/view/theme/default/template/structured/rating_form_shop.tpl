<form class="product-review" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label id="review-title">Ваше имя:</label>
		<input type="text" name="name" value="" class="form-control" placeholder="">
	</div>
	<div class="form-group">
		<label>Общая оценка магазина: </label>
	    <div class="review_stars_wrap">
			<div class="review_stars">
				<?php 
					 $star_4 = random_int(1, 99);
					 $star_3 = random_int(100, 199);
					 $star_2 = random_int(200, 299);
					 $star_1 = random_int(300, 599);
					 $star_0 = random_int(400, 499);
				 ?>
				<input id="star-<?php echo($star_4); ?>" class="star-4" type="radio" name="stars"/>
			    <label title="Отлично" for="star-<?php echo($star_4); ?>" style="width: auto;">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_3); ?>" class="star-3" type="radio" name="stars"/>
			    <label title="Хорошо" for="star-<?php echo($star_3); ?>" style="width: auto;">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_2); ?>" class="star-2" type="radio" name="stars" checked="checked" />
			    <label title="Нормально" for="star-<?php echo($star_2); ?>" style="width: auto;">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_1); ?>" class="star-1" type="radio" name="stars"/>
			    <label title="Плохо" for="star-<?php echo($star_1); ?>" style="width: auto;">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_0); ?>" class="star-0" type="radio" name="stars"/>
			    <label title="Ужасно" for="star-<?php echo($star_0); ?>" style="width: auto;">
			    	<i class="fas fa-star"></i>
			    </label>
			</div>
	    </div>
	</div>
	<div class="form-group">
		<label>Оценка сайта: </label>
	    <div class="review_stars_wrap2">
			<div class="review_stars2">
				<?php 
					 $star_9 = random_int(500, 599);
					 $star_8 = random_int(600, 699);
					 $star_7 = random_int(700, 799);
					 $star_6 = random_int(800, 899);
					 $star_5 = random_int(900, 999);
				 ?>
				<input id="star-<?php echo($star_9); ?>" class="star-9" type="radio" name="stars2"/>
			    <label title="Отлично" for="star-<?php echo($star_9); ?>">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_8); ?>" class="star-8" type="radio" name="stars2"/>
			    <label title="Хорошо" for="star-<?php echo($star_8); ?>">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_7); ?>" class="star-7" type="radio" name="stars2" checked="checked" />
			    <label title="Нормально" for="star-<?php echo($star_7); ?>">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_6); ?>" class="star-6" type="radio" name="stars2"/>
			    <label title="Плохо" for="star-<?php echo($star_6); ?>">
			    	<i class="fas fa-star"></i>
			    </label>
			    <input id="star-<?php echo($star_5); ?>" class="star-5" type="radio" name="stars2"/>
			    <label title="Ужасно" for="star-<?php echo($star_5); ?>">
			    	<i class="fas fa-star"></i>
			    </label>
			</div>
	    </div>
	</div>
	<div class="form-group">
		<label>Достоинства:</label>
		<textarea name="good" class="form-control" rows="2"></textarea>
	</div>
	<div class="form-group">
		<label>Недостатки:</label>
		<textarea name="bads" class="form-control" rows="2"></textarea>
	</div>
	<div class="form-group">
		<label>Комментарий:</label>
		<textarea name="text" class="form-control" rows="4"></textarea>
	</div>
	<div class="form-group">
		<label>Добавьте фото к отзыву (*.jpeg, *.png)</label>
		<div class="row">
			<div class="col-md-24"><input type="file" name="add-review-image" accept="image/jpeg,image/png,image/jpg" /></div>
		</div>
	</div>
	<div class="form-group-btn">
		<a id="button-review" class="btn btn-default">Отправить</a>
	</div>
</form>