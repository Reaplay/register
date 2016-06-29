{*<div class="alert alert-danger margin-bottom-30"><!-- DANGER -->
	<strong>Внимание</strong> Страница в тестовом режиме
</div>*}
<div class="panel panel-default">
	<div class="panel-body">
		Обновления, исправления - все добавляется сюда!<br />
		Если вы нашли ошибку, пишите на почту: svdodonov@alfabank.ru
	</div>
</div>

{foreach from=$changelog item=change}
	<ul class="list-unstyled">
		<li class="text-muted">
			<label><span class="width-100 inline-block">Version:</span> <strong class="text-warning">{$change.rev}</strong></label>
		</li>
		<li class="text-muted">
			<label><span class="width-100 inline-block">Date:</span> {if !$change.date}<span class="text-info">Pending / No ETA</span>{else}{$change.date}{/if}</label>
		</li>
		<li>
			<ul>
				{$change.text}
			</ul>
		</li>
		{if $IS_ADMINISTRATOR}
		<li class="text-muted">
			<label><a href="action_admin.php?module=changelog&action=edit&id={$change.id}">[E]</a><a href="action_admin.php?module=changelog&action=delete&id={$change.id}">[D]</a></label>
		</li>
		{/if}
	</ul>
		
	<div class="divider"><!-- divider --></div>
{/foreach}