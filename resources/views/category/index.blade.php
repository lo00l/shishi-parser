@extends('layout')

@section('title')Категории товаров@endsection

@section('content')
    {{ $categories->links() }}
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th colspan="8" class="text-center">Категории товаров</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Дата создания</th>
            <th>Ссылка на оригинал</th>
            <th>Изображение</th>
            <th>Оригинальное название</th>
            <th>Русское название</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->getAttribute('id') }}</td>
                <td>{{ $category->createTime }}</td>
                <td>
                    <a href="https://www.catalogue.shishi.ee/categories/{{ $category->getAttribute('original_id') }}" target="_blank" rel="noopener">{{ $category->getAttribute('original_id') }}</a>
                </td>
                <td><a href="{{ $category->getAttribute('preview_img') }}" target="_blank"><img class="preview" src="{{ $category->getAttribute('preview_img') }}" /></a> </td>
                <td>{{ $category->getAttribute('title') }}</td>
                <td><input type="text" class="input" value="{{ $category->getAttribute('russian_title') }}" data-id="{{ $category->getAttribute('id') }}" data-attribute="russian_title" /></td>
                <td><a href="{{ route('save_category', ['id' => $category->getAttribute('id')]) }}" class="btn btn-success" data-action="save" data-id="{{ $category->getAttribute('id') }}">Сохранить</a> </td>
                <td><a class="btn btn-default" href="{{ route('pages', ['category_id' => $category->getAttribute('id')]) }}">Страницы категории</a> </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}
@endsection