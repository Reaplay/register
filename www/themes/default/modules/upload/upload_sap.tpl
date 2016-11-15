{if !$step}
<form enctype="multipart/form-data" action="upload_sap.php?type=upload_client" method="POST" accept-charset="utf-8">
    <input type="text" class="form-control datepicker" data-format="dd/mm/yyyy" data-lang="ru" data-RTL="false" placeholder="Выберите дату данных" name="date_data">
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

