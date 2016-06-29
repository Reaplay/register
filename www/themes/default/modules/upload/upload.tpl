{*
<div class="alert alert-danger margin-bottom-30">
    <strong>Внимание</strong> В зависимости от заполнености файла, все загруженные клиенты могут быть прикреплены к вашей учетной записи.<br>
    Инструкция по загрузке: <a href="{$REL_CONFIG.defaultbaseurl}/page.php?id=1">Посмотреть</a> | <a href="{$REL_CONFIG.defaultbaseurl}/manual/manual_upload.docx">Скачать</a><br>
    Шаблон для загрузки: <a href="{$REL_CONFIG.defaultbaseurl}/manual/shablon_upload_client.csv">Скачать</a>
</div>
*}
{if !$step}
<form enctype="multipart/form-data" action="upload.php?type=upload_client" method="POST" accept-charset="utf-8">
    <input class="custom-file-upload" type="file" id="file" name="attachment" id="contact:attachment" data-btn-text="Выберите файл" accept=".csv" />
    <small class="text-muted block">Максимальный размер файла: 2Mb (только .csv)</small>
  {*  <select class="form-control  select2" name="action">
        <option value="0">Сделать всё</option>
        <option value="1">Только заполнение</option>
        <option value="2">Создать связи</option>
    </select>
    *}
    <input type="hidden" name="step" value="1">
    <button  class="btn btn-info">Загрузить</button>
</form>
{/if}

