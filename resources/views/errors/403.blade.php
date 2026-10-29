@extends('errors.layout')

@section('code', '403')
@section('icon', 'bi-shield-x')
@section('heading', 'Bạn không có quyền truy cập')
@section('message', $exception->getMessage() ?: 'Khu vực này chỉ dành cho Quản trị viên.')
