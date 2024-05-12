///////////////////////////////
////// Webcam Recording ///////
///////////////////////////////

let recording = false;
let mediaRecorder;
let recordedChunks = [];
let startTime;
const recordingLoading = document.getElementById('recording-loader');
const containerLoading = document.getElementById('container');

function startWebcam() {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then((stream) => {
            const webcam = document.getElementById('webcam');
            webcam.srcObject = stream;

            mediaRecorder = new MediaRecorder(stream, { mimeType: 'video/webm; codecs=vp9' }); // บีบอัดด้วย VP9
            mediaRecorder.ondataavailable = handleDataAvailable;
        })
        .catch((error) => {
            console.error('ไม่สามารถเปิดกล้อง:', error);
            const recordingError = document.getElementById('recording-error');
            const containerError = document.getElementById('container');
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
            timer: 500,
        }).then(() => {
            orderidReadonly.prop("readonly", false);
            $("#orderid").focus();
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
        formData.append('uploadVdoGoogleDrive', 'uploadVdoGoogleDrive');

        stopWebcam();

        $.ajax({
            type: 'POST',
            url: HOST_SERVER_NAME  + '/pages/record/server-record.php',
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
                console.log('Video uploaded to server:', response);

                if(response === 'success'){
                  const a = document.createElement('a');
                  document.body.appendChild(a);
                  a.style = 'display: none';
                  a.href = url;
                  a.download = orderid + '.webm';
                  a.click();

                  URL.revokeObjectURL(url);

                  containerLoading.style.background = 'none';
                  recordingLoading.style.display = 'none';
                  startWebcam();
                  record_system();
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

function record_system() {
    var dateSearch = $("#dateSearch").val();

    var formData = new FormData();
    formData.append('dateSearch', dateSearch);
    formData.append('record_system_list', 'record_system_list');

    $.ajax({
        type: 'POST',
        url: HOST_SERVER_NAME  + '/pages/record/server-record.php',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            var tb = $("#dbtable-record");
            var table = tb.DataTable();

            table.destroy();
            tb.html(response);
            // console.log(response); 

            tb.DataTable({
              dom: 'Bfrtip',
              buttons: [
                    {
                        extend: 'pageLength',
                        className: 'btn btn-sm btn-light-secondary mr-1',
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-light-success mr-1',
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-sm btn-light-danger mr-1',
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-light-primary mr-1',
                    },
              ],
              "columns": [
                { "width": "5%" },
                { "width": "2%" },
                { "width": "45%" },
                { "width": "18%" },
                { "width": "18%" },
                { "width": "12%" },
              ],
              "columnDefs": [
                { "orderable": false, "targets": 1 } // Disable sorting for the second column (index 1)
              ],
              "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
              order: [[0, 'desc']],
            });

        },
        error: function (error) {
            console.error('Error uploading video to server:', error);
        }
    });
}

window.onload = function() {
    startWebcam();
    record_system();
};
