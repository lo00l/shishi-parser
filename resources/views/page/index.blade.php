@extends('layout')

@section('title')Страницы товаров@endsection

@section('content')
    {{ $pages->links() }}
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th colspan="8" class="text-center">Страницы товаров</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Дата создания</th>
            <th>Ссылка на оригинал</th>
            <th>Изображение</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page->getAttribute('id') }}</td>
                <td>{{ $page->createTime }}</td>
                <td>
                    <a href="https://www.catalogue.shishi.ee/pages/{{ $page->getAttribute('original_id') }}" target="_blank" rel="noopener">{{ $page->getAttribute('original_id') }}</a>
                </td>
                <td><a href="{{ $page->getAttribute('preview_img') }}" target="_blank"><img class="preview" src="{{ $page->getAttribute('preview_img') }}" /></a> </td>
                <td><a class="btn btn-default" href="{{ route('products', ['page_id' => $page->getAttribute('id')]) }}">Товары страницы</a> </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        <a href="{{ route('save_all_pages') }}" class="btn btn-success btn-lg" data-action="save-all">Сохранить все</a>
    </div>
    {{ $pages->links() }}
@endsection