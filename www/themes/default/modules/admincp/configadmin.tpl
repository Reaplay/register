<div class="alert alert-danger margin-bottom-30"><!-- DANGER -->
	<strong>Внимание</strong> Страница в тестовом режиме
</div>
<div class="alert alert-danger margin-bottom-30"><!-- DANGER -->
	<strong>Внимание</strong> Где нажали кнопку "Сохранить", только та вкладка и сохранилась
</div>
<ul class="nav nav-tabs">
	<li class="active"><a href="#site" data-toggle="tab">Сайт </a></li>
	<li><a href="#crm" data-toggle="tab">CRM</a></li>
	<li><a href="#module" data-toggle="tab">Модули</a></li>
	<li><a href="#notify" data-toggle="tab">Уведомления</a></li>
	<li><a href="#limit" data-toggle="tab">Ограничения</a></li>
	<li><a href="#security" data-toggle="tab">Безопасность</a></li>
	<li><a href="#cache" data-toggle="tab">Кэш</a></li>
</ul>

<div class="tab-content">
	<div class="tab-pane fade in active" id="site">
		<form method="post" action="action_admin.php?module=configadmin&action=save">
		<div class="panel-body">
			<table class="table table-bordered table-striped" >
				<tbody> 
					<tr>         
						<td width="35%">Сайт включен?</td>
						<td width="65%"><select name="siteonline"><option value="1" {if $REL_CONFIG['siteonline'] == 1}selected="selected"{/if}">Да</option><option value="0" {if $REL_CONFIG['siteonline'] == 0}selected="selected"{/if}>Нет</option></select></td>
					</tr>
					<tr>         
						<td>Адрес сайта (без /):</td>
						<td><input type="text" name="defaultbaseurl" size="30" value="{$REL_CONFIG['defaultbaseurl']}"></td>
					</tr>  
					<tr>         
						<td width="35%">Емайл, с которого будут отправляться сообщения сайта:</td>
						<td width="65%"><input type="text" name="siteemail" size="30" value="{$REL_CONFIG['siteemail']}"></td>
					</tr>
					<tr>         
						<td>Емайл для связи с администратором:</td>
						<td><input type="text" name="adminemail" size="30" value="{$REL_CONFIG['adminemail']}"></td>
					</tr>  
					<tr>         
						<td width="35%">Стандартная тема:</td>
						<td width="65%"><input type="text" name="default_theme" size="10" value="{$REL_CONFIG['default_theme']}"></td>
					</tr>
					<tr>         
						<td>Копирайт</td>
						<td><input type="text" name="yourcopy" size="60" value="{$REL_CONFIG['yourcopy']}"></td>
					</tr>
					<tr>         
						<td width="35%">Использовать систему блоков (отключать не рекомендуется):</td>
						<td width="65%"><select name="use_blocks"><option value="1" {if $REL_CONFIG['use_blocks']==1}selected="selected"{/if})}>Да</option><option value="0" {if $REL_CONFIG['use_blocks']==0}selected="selected"{/if})}>Нет</option></select></td>
					</tr>
					<tr>         
						<td>Таймзона</td>
						<td> Не готово{* {list_timezones('site_timezone',$REL_CONFIG['site_timezone'])}  если активировать, то добавлять в configadmin.php между верхней и нижней настройкой*}</td>
					</tr> 
					<tr>         
						<td width="35%">Использовать gzip сжатие для страниц:</td>
						<td width="65%"><select name="use_gzip"><option value="1" {if $REL_CONFIG['use_gzip']==1}selected{/if}>Да</option><option value="0" {if $REL_CONFIG['use_gzip']==0}selected{/if}>Нет</option></select></td>
					</tr>

					</tbody>
				</table>
				<input type="hidden" name="type" value="site">
				<button type="submit" class="btn btn-3d btn-teal btn-slg btn-block" type="submit">
					Сохранить изменения
				</button>
			</div>
		</form>
	</div>
	<div class="tab-pane fade" id="crm">
		<form method="post" action="action_admin.php?module=configadmin&action=save">
		<div class="panel-body">
			<table class="table table-bordered table-striped" >
				<tbody> 
					<tr>         
						<td width="35%">Кол-во клиентов на одной странице:</td>
						<td width="65%"><input type="text" name="per_page_clients" size="30" value="{$REL_CONFIG['per_page_clients']}"></td>
					</tr>
					<tr>         
						<td width="35%">Кол-во пользователей на одной странице:</td>
						<td width="65%"><input type="text" name="per_page_users" size="30" value="{$REL_CONFIG['per_page_users']}"></td>
					</tr>
					<tr>
						<td width="35%">Кол-во отделений на одной странице:</td>
						<td width="65%"><input type="text" name="per_page_department" size="30" value="{$REL_CONFIG['per_page_department']}"></td>
					</tr>
					<tr>
						<td width="35%">Кол-во отзвонов на одной странице:</td>
						<td width="65%"><input type="text" name="per_page_callback" size="30" value="{$REL_CONFIG['per_page_callback']}"></td>
					</tr>
					<tr>
						<td width="35%">Кол-во карт на одной странице:</td>
						<td width="65%"><input type="text" name="per_page_card" size="30" value="{$REL_CONFIG['per_page_card']}"></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="type" value="crm">
			<button type="submit" class="btn btn-3d btn-teal btn-slg btn-block" type="submit">
				Сохранить изменения
			</button>
		</div>
		</form>
	</div>
	<div class="tab-pane fade" id="module">
		<form method="post" action="action_admin.php?module=configadmin&action=save">
		<div class="panel-body">
			<table class="table table-bordered table-striped" >
				<tbody> 
					<tr>         
						<td width="35%">Включить раздел "Пользователи":</td>
						<td width="65%"><select name="deny_users"><option value="1" {if $REL_CONFIG['deny_users']==1}selected="selected"{/if}>Да</option><option value="0" {if $REL_CONFIG['deny_users']==0}selected="selected"{/if}>Нет</option></select></td>
					</tr>
					<tr>
						<td width="35%">Включить раздел "Клиенты":</td>
						<td width="65%"><select name="deny_client"><option value="1" {if $REL_CONFIG['deny_client']==1}selected="selected"{/if}>Да</option><option value="0" {if $REL_CONFIG['deny_client']==0}selected="selected"{/if}>Нет</option></select></td>
					</tr>
					<tr>
						<td width="35%">Включить раздел "Карты":</td>
						<td width="65%"><select name="deny_card"><option value="1" {if $REL_CONFIG['deny_card']==1}selected="selected"{/if}>Да</option><option value="0" {if $REL_CONFIG['deny_card']==0}selected="selected"{/if}>Нет</option></select></td>
					</tr>
					<tr>
						<td width="35%">Включить раздел "Статистика":</td>
						<td width="65%"><select name="deny_statistic"><option value="1" {if $REL_CONFIG['deny_statistic']==1}selected="selected"{/if}>Да</option><option value="0" {if $REL_CONFIG['deny_statistic']==0}selected="selected"{/if}>Нет</option></select></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="type" value="module">
			<button type="submit" class="btn btn-3d btn-teal btn-slg btn-block" type="submit">
				Сохранить изменения
			</button>
		</div>
		</form>
	</div>
	<div class="tab-pane fade" id="notify">
		<form method="post" action="action_admin.php?module=configadmin&action=save">
		<div class="panel-body">
			<table class="table table-bordered table-striped" >
				<tbody> 
					<tr>         
						<td width="35%">Стандартные уведомления (вспл.окно и/или ЛС):</td>
						<td width="65%"><input type="text" name="default_notifs" size="" value="{$REL_CONFIG['default_notifs']}"></td>
					</tr>
					<tr>         
						<td>Стандартные уведомления в Email:</td>
						<td><input type="text" name="default_emailnotifs" size="" value="{$REL_CONFIG['default_emailnotifs']}"><br/><small>unread,users,reports,unchecked ; Подробнее - <a target="_blank" href="{$REL_SEO->make_link('mynotifs','settings','')}">настройки моих уведомлений</a></small></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="type" value="notify">
			<button type="submit" class="btn btn-3d btn-teal btn-slg btn-block" type="submit">
				Сохранить изменения
			</button>
		</div>
		</form>
	</div>
	<div class="tab-pane fade" id="limit">
		<form method="post" action="action_admin.php?module=configadmin&action=save">
		<div class="panel-body">
				<table class="table table-bordered table-striped" >
					<tbody> 
						<tr>         
							<td width="35%">Максимальное количество пользователей:</td>
							<td width="65%"><input type="text" name="maxusers" size="6" value="{$REL_CONFIG['maxusers']}">пользователей, укажите 0 для отключения лимита</td>
						</tr>
						<tr>         
							<td>Максимальное количество сообщений в Личном ящике:</td>
							<td><input type="text" name="pm_max" size="4" value="{$REL_CONFIG['pm_max']}">сообщений</td>
						</tr>
						<tr>
							<td>Максимальное время хранения системных сообщений:</td>
							<td><input type="text" name="pm_delete_sys_days" size="4" value="{$REL_CONFIG['pm_delete_sys_days']}">дней</td>
						</tr>
						<tr>
							<td>Максимальное время хранения сообщений от пользователей:</td>
							<td><input type="text" name="pm_delete_user_days" size="4" value="{$REL_CONFIG['pm_delete_user_days']}">дней</td>
						</tr>
					</tbody>
				</table>
			<input type="hidden" name="type" value="limit">
			<button type="submit" class="btn btn-3d btn-teal btn-slg btn-block" type="submit">
				Сохранить изменения
			</button>
			</div>
		</form>
	</div>
	<div class="tab-pane fade" id="security">
		<form method="post" action="action_admin.php?module=configadmin&action=save">
		<div class="panel-body">
				<table class="table table-bordered table-striped" >
					<tbody> 
						<tr>         
							<td width="35%">SQL/Cron debug</td>
							<td width="65%"><select name="debug_mode"><option value="1" {if $REL_CONFIG['debug_mode']==1}selected="selected"{/if}>Да</option><option value="0" {if $REL_CONFIG['debug_mode']==0}selected="selected"{/if}>Нет</option></select></td>
						</tr>
						<tr>         
							<td>Template debug</td>
							<td><select name="debug_template"><option value="1" {if $REL_CONFIG['debug_template']==1}selected="selected"{/if}>Да</option><option value="0" {if $REL_CONFIG['debug_template']==0}selected="selected"{/if}>Нет</option></select></td>
						</tr>
					</tbody>
				</table>
			<input type="hidden" name="type" value="security">
			<button type="submit" class="btn btn-3d btn-teal btn-slg btn-block" type="submit">
				Сохранить изменения
			</button>
			</div>
		</form>
	</div>
	<div class="tab-pane fade" id="cache">
		<form method="post" action="action_admin.php?module=configadmin&action=save">
		<div class="panel-body">
			<table class="table table-bordered table-striped" >
				<tbody>
				<tr>
					<td>Включить кеш шаблонов?</td>
					<td><select name="cache_template"><option value="1" {if $REL_CONFIG['cache_template']==1}selected="selected"{/if}>Да</option><option value="0" {if $REL_CONFIG['cache_template']==0}selected="selected"{/if}>Нет</option></select>  <small>Включение кэширует ВСЕ страницы в одном положении. <b>Не рекомендуется</b> включать</small></td>
				</tr>
				<tr>
					<td width="35%">Время жизни кеша шаблонов</td>
					<td width="65%"><input name="cache_template_time" size="3" value="{$REL_CONFIG['cache_template_time']}"> Секунд</td>
				</tr>
				<tr>
					<td width="35%">Время жизни кеша общей статистики</td>
					<td width="65%"><input name="cache_statistic_all" size="3" value="{$REL_CONFIG['cache_statistic_all']}"> Секунд</td>
				</tr>
				</tbody>
			</table>
			<input type="hidden" name="type" value="cache">
			<button type="submit" class="btn btn-3d btn-teal btn-slg btn-block" type="submit">
				Сохранить изменения
			</button>
		</div>
		</form>
	</div>

</div>



{*
<div class="padding-20" id="content">
		<div class="panel panel-default">
		<div class="panel-heading panel-heading-transparent">
			<strong>Настройки CRM</strong>
		</div>
		<div class="panel-body">
			<table class="table table-bordered table-striped" >
				<tbody> 
					<tr>         
						<td width="35%"></td>
						<td width="65%"></td>
					</tr>
					<tr>         
						<td></td>
						<td></td>
					</tr> 
				</tbody>
			</table>
		</div>
	</div>
</div>
*}


