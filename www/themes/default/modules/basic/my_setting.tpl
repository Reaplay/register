<div class="col-lg-9 col-md-9 col-sm-8 col-lg-push-3 col-md-push-3 col-sm-push-4 ">

	{if $msg}
		<div class="alert alert-{$msg.class} margin-bottom-30">
			{$msg.text}
		</div>
	{/if}

						<ul class="nav nav-tabs nav-top-border">
							<li class="active"><a data-toggle="tab" href="#info" aria-expanded="false">Персональная информация</a></li>
							<li class=""><a data-toggle="tab" href="#password" aria-expanded="true">Пароль</a></li>
							<li class=""><a data-toggle="tab" href="#setting" aria-expanded="false">Доп. настройки</a></li>
							<li class=""><a data-toggle="tab" href="#register_user" aria-expanded="false">Пользвательский реестр</a></li>
						</ul>

						<div class="tab-content margin-top-20 col-md-7">

							<!-- PERSONAL INFO TAB -->
							<div id="info" class="tab-pane fade active in">
								В разработке...
							</div>
							<!-- /PERSONAL INFO TAB -->


							<!-- PASSWORD TAB -->
							<div id="password" class="tab-pane fade">

								<form method="post" action="my_setting.php">

									<div class="form-group">
										<label class="control-label">Текущий пароль</label>
										<input type="password" class="form-control" name="old_password" value="">
									</div>
									<div class="form-group">
										<label class="control-label">Новый пароль</label>
										<input type="password" class="form-control" name="new_password" value="">
									</div>
									<div class="form-group">
										<label class="control-label">Повторите пароль</label>
										<input type="password" class="form-control" name="re_new_password" value="">
									</div>

									<div class="margiv-top10">
										<button class="btn btn-primary" ><i class="fa fa-check"></i> Изменить пароль</button>
									</div>
									<input type="hidden" value="yes" name="change_password">
								</form>

							</div>
							<!-- /PASSWORD TAB -->

							<!-- PRIVACY TAB -->
							<div id="setting" class="tab-pane fade">

								<form method="post" action="my_setting.php">
									<div class="sky-form">

										<table class="table table-bordered table-striped">
											<tbody>
												{*<tr>
													<td>Уведомления по почте</td>
													<td>
														<div class="inline-group">
															<label class="radio nomargin-top nomargin-bottom">
																<input type="radio" checked="" name="radioOption"><i></i> Да
															</label>

															<label class="radio nomargin-top nomargin-bottom">
																<input type="radio" checked="" name="radioOption"><i></i> Нет
															</label>
														</div>
													</td>
												</tr>
												<tr>
													<td>Показывать "чужих" клиентов</td>
													<td>
														<label class="checkbox nomargin">
															<input type="checkbox" checked="" name="checkbox"><i></i> Да
														</label>
													</td>
												</tr>*}
												<tr>
													<td>Показывать уведомления от "системы"</td>
													<td>
														<label class="checkbox nomargin">
															<input type="checkbox" {if !$CURUSER.notifs}checked=""{/if} name="notify"><i></i> Да
														</label>
													</td>
												</tr>
												{*<tr>
													<td>Уведомления</td>
													<td>
														<label class="checkbox nomargin">
															<input type="checkbox" checked="" name="checkbox"><i></i> Да
														</label>
													</td>
												</tr>*}
											</tbody>
										</table>

									</div>

									<div class="margin-top-10">
										<input type="hidden" value="yes" name="change_setting">

										<button class="btn btn-primary" ><i class="fa fa-check"></i> Сохранить изменения </button>
										<a class="btn btn-default" href="#">Отмена </a>
									</div>

								</form>

							</div>
							<!-- /PRIVACY TAB -->

							<div id="register_user" class="tab-pane fade">
								<form method="post" action="my_setting.php">
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.1}checked="checked"{/if} value="1">
											<i></i>Идентификатор штатной должности
										</label><br/>

									</div>

									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.2}checked="checked"{/if} value="2">
											<i></i> ФИО
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.3}checked="checked"{/if} value="3">
											<i></i> Должность
										</label>
									</div>

									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.4}checked="checked"{/if} value="4">
											<i></i> Функц. сфера
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.5}checked="checked"{/if} value="5">
											<i></i> Название подразделение
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.6}checked="checked"{/if} value="6">
											<i></i> ДО/ОО
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.7}checked="checked"{/if} value="7">
											<i></i> Вложенное подразделение 1
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.8}checked="checked"{/if} value="8">
											<i></i> Вложенное подразделение 2
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.9}checked="checked"{/if} value="9">
											<i></i> Вложенное подразделение 3
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.10}checked="checked"{/if} value="10">
											<i></i> Вложенное подразделение 4
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.11}checked="checked"{/if} value="11">
											<i></i> Дирекция ДРО
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.12}checked="checked"{/if} value="12">
											<i></i> Курирующий член УК
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.13}checked="checked"{/if} value="13">
											<i></i> Куратор в ЦО
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.14}checked="checked"{/if} value="14">
											<i></i> ФР в ЦО или регионе
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.15}checked="checked"{/if} value="15">
											<i></i> ФИО ФР
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.16}checked="checked"{/if} value="16">
											<i></i> email  ФР
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.17}checked="checked"{/if} value="17">
											<i></i> Должность ФР
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.18}checked="checked"{/if} value="18">
											<i></i> ФИО АР
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.19}checked="checked"{/if} value="19">
											<i></i> Должность АР
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.20}checked="checked"{/if} value="20">
											<i></i> Группа функций
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.21}checked="checked"{/if} value="21">
											<i></i> Функция
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.22}checked="checked"{/if} value="22">
											<i></i> Стратегический проект
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.23}checked="checked"{/if} value="23">
											<i></i> ЦО/РЦК/РП
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.24}checked="checked"{/if} value="24">
											<i></i> МВЗ
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.25}checked="checked"{/if} value="25">
											<i></i> Модель
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.26}checked="checked"{/if} value="26">
											<i></i> Штат?
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.27}checked="checked"{/if} value="27">
											<i></i> Драфт
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.28}checked="checked"{/if} value="28">
											<i></i> Факт
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.29}checked="checked"{/if} value="29">
											<i></i> FTE
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.30}checked="checked"{/if} value="30">
											<i></i> Дата ввода шт.ед. в штатное расписание по приказу
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.31}checked="checked"{/if} value="31">
											<i></i> Ш.Е. передана?
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.32}checked="checked"{/if} value="32">
											<i></i> Дата приема сотрудника на работу
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.33}checked="checked"{/if} value="33">
											<i></i> Дата перевода
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.34}checked="checked"{/if} value="34">
											<i></i> Город
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.35}checked="checked"{/if} value="35">
											<i></i> Адрес
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.36}checked="checked"{/if} value="36">
											<i></i> Этаж
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.37}checked="checked"{/if} value="37">
											<i></i> Комната
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.38}checked="checked"{/if} value="38">
											<i></i> Место
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.39}checked="checked"{/if} value="39">
											<i></i> Готовность
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.40}checked="checked"{/if} value="40">
											<i></i> Дата готовности
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.41}checked="checked"{/if} value="41">
											<i></i> Бронь?
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.42}checked="checked"{/if} value="42">
											<i></i> Дата бронирования
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.43}checked="checked"{/if} value="43">
											<i></i> Свободно?
										</label>
									</div>
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.44}checked="checked"{/if} value="44">
											<i></i> Занято?
										</label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label class="checkbox">
											<input type="checkbox" name="register_user[]" {if !$user_view.45}checked="checked"{/if} value="45">
											<i></i>Дата занятия
										</label>
									</div>
									<div class="col-md-6">

									</div>
								</div>
									<div class="margiv-top10">
										<button class="btn btn-primary" ><i class="fa fa-check"></i> Сохранить изменения</button>
									</div>
									<input type="hidden" value="yes" name="change_register_user">
									<input type="hidden" value="45" name="count_filter_user">
							</form>
								
							</div>


						</div>

					</div>