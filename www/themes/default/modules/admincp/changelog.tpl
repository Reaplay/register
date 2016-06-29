{if $msg}
	<div class="alert alert-default margin-bottom-30">
{$msg}
</div>
{/if}
<form class="nomargin sky-form boxed block-review-content" action="action_admin.php?module=changelog&action={if $data_changelog}edit&id={$data_changelog.id}{else}add{/if}" method="post">
	<header>
		<i class="fa fa-users"></i> Список изменений
	</header>

	<fieldset class="nomargin">
			
		<div class="row margin-bottom-10">
			<div class="col-md-6">
				<div class="fancy-form">
				<i class="fa fa-cogs"></i>
				<input name="num_rev" type="text" class="form-control" placeholder="Номер версии" value="{$data_changelog.rev}">
				<span class="fancy-tooltip top-left"> <!-- positions: .top-left | .top-right -->
					<em>Введите номер версии</em>
				</span>
				</div>
			</div>
		
			<div class="col col-md-6">
				<div class="fancy-form">
					<i class="fa fa-calendar"></i>
					<input type="text" class="form-control datepicker" data-format="dd/mm/yyyy" data-lang="ru" data-RTL="false" value="{$data_changelog.date}" name="date">
			
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<div class="col-md-12 col-sm-12">
					<label>Описание изменений</label>
					<textarea class="summernote form-control" data-height="200" data-lang="en-US" name="text">{$data_changelog.text}</textarea>
					
				</div>
			</div>
		</div>

		</fieldset>

	<div class="row margin-bottom-20">
		<div class="col-md-12">
			<input type="hidden" name = "id" value="{$data_changelog.id}">
			<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> {if $data_changelog}Изменить{else}Добавить{/if}</button>
		</div>
	</div>

</form>