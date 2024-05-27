////////////////////////////////
/////////// QR-Code ////////////
////////////////////////////////

function setValue(count) {
    
}

document.getElementById('webcam-select').addEventListener('change', (event) => {
    startWebcam(event.target.value);
});

function startWebcam(deviceId) {

    const webcam = document.getElementById('webcam');
    const recordingError = document.getElementById('recording-error');
    const containerError = document.getElementById('container');

    navigator.mediaDevices.getUserMedia({
        video: { deviceId: deviceId ? { exact: deviceId } : undefined },
        audio: false
    })
    .then((stream) => {
        webcam.srcObject = stream;
        containerError.style.background = null;
        recordingError.style.display = 'none';
        mediaRecorder = new MediaRecorder(stream, { mimeType: 'video/webm; codecs=vp9' });
        mediaRecorder.ondataavailable = handleDataAvailable;
    })
    .catch((error) => {
        webcam.srcObject = null;
        containerError.style.background = 'black';
        recordingError.style.display = 'block';
    });
}


function stopWebcam() {
    const webcam = document.getElementById('webcam');
    const tracks = webcam.srcObject.getTracks();

    tracks.forEach(track => track.stop());
    webcam.srcObject = null;
}


function checkOrderID() {
    if ($("#orderid").val()  === "") {
        $("#orderid").removeClass("is-valid").addClass("is-invalid");
    }else{
        $("#orderid").removeClass("is-invalid").addClass("is-valid");
    }
}

function toggleRecording() {
    const recordingIndicator = document.getElementById('recording-indicator');
    const recordingBadge = document.getElementById('recording-badge');
    const timerElement = document.getElementById('timer');
    var orderid = $("#orderid").val();
    var orderidReadonly = $("#orderid");

    if (orderid === null || orderid.trim() === "") {
        Swal.fire({
            title: 'WARNING!',
            text: 'Order-ID is required and cannot be empty!',
            icon: 'warning',
            allowOutsideClick: false,
        }).then(() => {
            orderidReadonly.prop("readonly", false);
            $("#orderid").focus();
            $("#orderid").removeClass("is-valid").addClass("is-invalid");
        });
        return;
    }

    if (recording) {
        mediaRecorder.stop();
        document.getElementById('record-text').innerHTML = '<i class="bi bi-play-fill"></i> Record';
        recordingIndicator.style.display = 'none';
        orderidReadonly.prop("readonly", false);
    } else {
        orderidReadonly.prop("readonly", true);
        recordedChunks = [];
        startTime = new Date().getTime();
        mediaRecorder.start();

        document.getElementById('record-text').innerHTML = '<i class="bi bi-stop-fill"></i> Pause Record';
        recordingIndicator.style.display = 'inline-block';

        // เริ่มจับเวลาทุก 1 วินาที
        setInterval(() => {
            const currentTime = new Date().getTime();
            const elapsedTime = new Date(currentTime - startTime);

            const hours = String(elapsedTime.getUTCHours()).padStart(2, '0');
            const minutes = String(elapsedTime.getUTCMinutes()).padStart(2, '0');
            const seconds = String(elapsedTime.getUTCSeconds()).padStart(2, '0');
            const milliseconds = String(elapsedTime.getUTCMilliseconds()).padStart(3, '0');

            timerElement.innerText = `${hours}:${minutes}:${seconds}`;
        }, 1000);
    }

    recording = !recording;
}

function handleDataAvailable(event) {
    if (event.data.size > 0) {
        recordedChunks.push(event.data);
        saveVideo();
    } else {
        console.error('No data available for recording');
    }
}

function saveVideo() {
    if (recordedChunks.length > 0) {
        const blob = new Blob(recordedChunks, { type: 'video/webm' });
        const url = URL.createObjectURL(blob);
        var orderid = $("#orderid").val();

        var formData = new FormData();
        formData.append('record_name', blob, 'recorded-video.webm');
        formData.append('order_id', orderid);
        formData.append('method', 'uploadVdoGoogleDrive');

        stopWebcam();

        $.ajax({
            type: 'POST',
            url: JSON_HOST_NAME_URL  + 'record/record-function.php',
            data: formData,
            contentType: false,
            processData: false,
            xhr: function() {

                var xhr = new window.XMLHttpRequest();
                containerLoading.style.background = 'black';
                recordingLoading.style.display = 'block';

                xhr.upload.addEventListener('progress', function(event) {
                    if (event.lengthComputable) {
                        var percentComplete = (event.loaded / event.total) * 100;
                        console.log('Progress: ' + percentComplete.toFixed(2) + '%');

                        $('#progress-bar-container .progress-bar').css('width', percentComplete + '%');
                        $('#process-number').html(' [ <i class="bi bi-cloud-download"></i> Save Process : ' + percentComplete + ' %  <i class="bi bi-floppy"></i> ] ');
                    }
                }, false);

                return xhr;
            },
            success: function (response) {
                var jsonData = JSON.parse(response);
                console.log('Video uploaded to server:', jsonData.status);
                
                if(jsonData.status === "success"){
                  const a = document.createElement('a');
                  document.body.appendChild(a);
                  a.style = 'display: none';
                  a.href = url;
                  a.download = orderid + '.webm';
                  a.click();

                  URL.revokeObjectURL(url);

                  containerLoading.style.background = 'none';
                  recordingLoading.style.display = 'none';
                  $("#orderid").val('');
                  $("#orderid").removeClass("is-valid").removeClass("is-invalid");
                  startWebcam();
                  table_record_system();
                }

            },
            error: function (error) {
                console.error('Error uploading video to server:', error);
            }
        });
    }
}

document.addEventListener("keypress", function(e) {
    if (e.key === 'Enter') {
        toggleRecording();
    }
});




///////////////////////////////
/////// Table Recording ///////
//////////////////////////////

function table_record_system() {
    var dateSearch = $("#dateSearch").val();

    var formData = new FormData();
    formData.append('dateSearch', dateSearch);
    formData.append('method', 'record_system_list');

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL  + 'record/record-function.php',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            
            var table = $("#dbtable-record");
            var jsonData = JSON.parse(response);

            var dataTableJson = {
                                    lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                                    columnDefs: [
                                        { "width": "2%"  , "targets": [0, 5] },
                                        { "width": "5%"  , "targets": [1] },
                                        { "width": "14.6666%" , "targets": [2, 3, 4] },
                                        { className: "dt-left", targets: [2] },
                                        { className: "dt-right", targets: [3, 4] },
                                        { className: "dt-center", targets: [0, 1, 5] },
                                        { orderable: false, targets: [1, 2, 3, 4, 5] },
                                    ],
                                    order: [ [ 0, "desc" ] ],
                                }
            if ($.fn.DataTable.isDataTable(table)) {
                table.DataTable().destroy();
            }
            table.html(jsonData.thead + jsonData.tload);
            table.DataTable(dataTableJson);

            setTimeout(function() {
                table.DataTable().destroy();
                table.html(jsonData.thead + jsonData.tbody);
                table.DataTable(dataTableJson);
                shiftKeyCheckbox();
            }, 500);
            
        },
        error: function (error) {
            console.error('Error uploading video to server:', error);
        }
    });
}

function deleteRecord(id) {

    var formData = {
        id      : id,
        method  : "deleteRecord",
    };

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: JSON_HOST_NAME_URL  + 'record/record-function.php',
                data: formData,
                success: function (response) {
                    var jsonData = JSON.parse(response);
                    
                    if(jsonData.status === "success"){
                        notifier.show('Deleted!', 'Your record has been deleted.', 'danger', NOTIFIER_IMAGE_DANGER, 4000);
                    }

                    table_record_system();
                },
                error: function (error) {
                    console.error('Error uploading video to server:', error);
                }
            });
        }
    });
    
}



document.addEventListener('DOMContentLoaded', function() {
    const addDatepicker = new Datepicker(document.querySelector('#dateSearch'), {
      pickLevel   : 0,
      buttonClass : 'btn',
      format      : 'yyyy-mm-dd',
      todayButton : true,
      clearButton : true,
      language    : 'en',
    });
  
    // เลือกองค์ประกอบของไอคอนปฏิทิน
    const iconDatepicker = document.querySelector('#iconDatepicker');
  
    // เพิ่มกึ่งเปิดและปิดการทำงานเมื่อคลิกที่ไอคอนปฏิทิน
    iconDatepicker.addEventListener('click', function() {
      addDatepicker.show();
    });

});

window.onload = function() {
    startWebcam();
    table_record_system();
    getWebcamDrivers();
};
