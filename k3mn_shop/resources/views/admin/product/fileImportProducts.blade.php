@extends('adminlte::page')

@section('title', 'Tạo sản phẩm bằng file')

@section('content_header')
    
    
@stop

@section('content')
    <!-- Nội dung chính của trang quản trị -->
  <form action="{{ route('importProductsByFile') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card card-primary">
      <div class="card-header">
        <h1 class="card-title">Nhập file chứa dữ liệu sản phẩm</h1>
      </div>
      <!-- /.card-header -->
      <div class="card-body col-md-8">
        <div id="file-input">
          <input type="file" name="file-import-products" id="customFile">
          <!-- <label for="customFile">Choose file</label> -->
        </div>
        <br>
        <div>          
          <button type="submit" class="btn btn-success float-right">Xác nhận</button>
          <a href="{{ route('adminCategory.index') }}" class="btn btn-danger float-right">Hủy bỏ</a>
        </div> 
      </div>
      <!-- /.card-body -->
    </div>
  </form>
@stop

@section('css')
  <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
<style>
  #file-input {
    margin-bottom: 20px;
  }
</style>
@stop

@section('js')
<script>
    
</script>
@stop