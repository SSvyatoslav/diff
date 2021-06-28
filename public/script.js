const myForm = document.getElementById('myForm');
const inpFile = document.getElementById('inpFile');
const formDiff = document.getElementById('formDiff');
let files = [];

myForm.addEventListener('submit', e => {
    e.preventDefault();

    const endpoint = 'diff.php';
    const formData = new FormData();

    if (inpFile.value == '') {
        alert('файл не выбран');
        return false;
    }else {
        formData.append('inpFile', inpFile.files[0]);
    }


    fetch(endpoint, {
        method: "post",
        body: formData
    }).then(function(response) {
        if(response.ok) {
            let parent = document.querySelector('#fileList');
            let li = document.createElement('li');
            li.innerHTML = inpFile.files[0].name;
            parent.appendChild(li);

            files.push(inpFile.files[0].name);
            inpFile.value = '';

        } else {
            console.log('Проблема с сетью');
        }
    }).catch(console.error);

});

formDiff.addEventListener('submit', e => {
    e.preventDefault();

    if(files.length < 2){
        alert('Файлы не выбраны или их меньше 2');
        return false;
    }

    const endpoint = 'diff.php';
    const formData = new FormData();

    formData.append('files', files);

    fetch(endpoint, {
        method: "post",
        body: formData
    }).then(function (response) {
        return response.text();
    }).then(function (body) {
        let results = $.parseJSON(body);

        $('.result_h2').show();

        Object.values(results).forEach(function(item, i) {

            // если объект с файлами
            if(typeof item == 'object'){

                $.each( item, function( key, value) {

                    // Заголовок
                    $('#files').append('<div id="' + key + '"><h2>Файл - ' +  key +'</h2></div>');

                    value = String(value);

                    let values = value.split(",");

                    // добавление тела файла
                    $.each(values, function (value, key_) {
                        $('#'+ key).append('<p>'+ key_ + '</p>');
                    });

                });
            }else{
                // добавим в табличку результаты
                let re = '/',
                    nameList = item.split(re);
                $('#result').append('<tr><td>' + i +'</td><td>' + nameList[0] +'</td><td>'+ nameList[1] + '</td></tr>');
            }

        });

    }).catch(console.error);
});