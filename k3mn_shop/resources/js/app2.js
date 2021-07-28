require('./bootstrap');

Echo.private('notifications_for_admin')
.listen('NewImportFileStatus', (data) => {
  console.log(data);
  var status = data.status;
  if ( status === 'finished' ) {
    document.getElementById('message-status').innerText = "Xử lý tệp thành công!";
    document.getElementById('link-admin-page').style.display = "block";
  } else if ( status === 'failed' ) {
    document.getElementById('message-status').innerText = "Xử lý tệp thất bại. Vui lòng thử lại!";
    // document.getElementById('message-status').style.color = "red";
    document.getElementById('message-status').style.fontWeight = "bold";
  }
  var progress_bar = document.getElementById('progress-import');
  progress_bar.style.width = `${data.progress_percentage}%`;
  progress_bar.innerText = `${data.progress_percentage}%`;
}); 