

	<!-- FILTER -->
	<ul class="nav nav-pills mix-filter margin-bottom-30">
		<li class="filter active" data-filter="all"><a href="#">Все</a></li>
		<li class="filter" data-filter="client"><a href="#">Клиенты</a></li>
		<li class="filter" data-filter="card"><a href="#">Карты</a></li>
		<li class="filter" data-filter="user"><a href="#">Пользователи</a></li>
		<li class="filter" data-filter="other"><a href="#">Прочее</a></li>
	</ul>
	<!-- /FILTER -->

	<div class="row mix-grid">

		<!-- LEFT COLUMNS -->
		<div class="col-md-9">

			<!-- TOGGLES -->
			<div class="toggle toggle-transparent toggle-bordered-simple" style="   ">
				{foreach from=$data_faq item=faq}
				<div class="toggle mix {$faq.type} mix_all" style=" display: block; opacity: 1;"><!-- toggle -->
					<label>{counter}. {$faq.title}</label>
					<div class="toggle-content" style="display: none;">

						<p class="clearfix">
							{$faq.text}

						</p>
						<div class="border-top-1 padding-10">
							<span class="pull-left size-11 margin-top-3 text-muted">Вопрос добавлен: {$faq.added} (Последние изменение: {$faq.edited})</span>

						</div>
					</div>
				</div><!-- /toggle -->
				{/foreach}


			</div>
			<!-- /TOGGLES -->

			<!-- CALLOUT -->
			<div class="callout alert alert-border margin-top-60 margin-bottom-60">

				<div class="row">

					<div class="col-md-9 col-sm-12"><!-- left text -->
						<h4>Нет нужного ответа? Звоните <strong>(0521) 397</strong></h4>
						<p class="font-lato size-17">
							Данная страница будет обновляться
						</p>
					</div><!-- /left text -->


					<div class="col-md-3 col-sm-12 text-right"><!-- right btn -->
						<a  class="btn btn-default btn-lg"href="mailto:{$REL_CONFIG.adminemail}">Написать письмо</a>

					</div><!-- /right btn -->

				</div>

			</div>
			<!-- /CALLOUT -->

		</div>
		<!-- /LEFT COLUMNS -->



	</div>

	{*

	 <p class="lead">Здесь собраны ответы на самые популярные вопросы. Наверное.</p>

<div class="divider divider-center divider-color nomargin-top margin-bottom-80"><!-- divider -->
	<i class="fa fa-chevron-down"></i>
</div>

<div class="columnize-2">
	
{foreach from=$data_faq item=faq}
	<h2 class="size-20">{$faq.title}</h2>
	<p>
		{$faq.text}
	</p>

	
	
{/foreach}


</div>

<!-- FEEDBACK -->
<div class="callout alert alert-border margin-top-60 margin-bottom-60">

	<div class="row">

		<div class="col-md-9 col-sm-12"><!-- left text -->
			<h4>Не нашли ответ на ваш вопрос? <strong>Пишите</strong> и быть может мы ответим!</h4>
			<p class="font-lato size-17">
				Если не сильно заняты
			</p>
		</div><!-- /left text -->

		
		<div class="col-md-3 col-sm-12 text-right"><!-- right btn -->
			<a href="#" class="btn btn-default btn-lg">Написать</a>
		</div><!-- /right btn -->

	</div>

</div>
<!-- /FEEDBACK -->

*}