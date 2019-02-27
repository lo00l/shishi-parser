@extends('layout')

@section('title')Товары@endsection

@section('content')
    {{ $products->links() }}
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th colspan="9" class="text-center">Товары</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Дата создания</th>
            <th>Ссылка на оригинал</th>
            <th>Изображение</th>
            <th>Оригинальное название</th>
            <th>Русское название</th>
            <th>Оригинальное описание</th>
            <th>Русское описание</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr data-id="{{ $product->getAttribute('id') }}">
                <td>{{ $product->getAttribute('id') }}</td>
                <td>{{ $product->createTime }}</td>
                <td>
                    <a href="https://www.catalogue.shishi.ee/products/{{ $product->getAttribute('original_id') }}" target="_blank" rel="noopener">{{ $product->getAttribute('original_id') }}</a>
                </td>
                <td><a href="{{ $product->getAttribute('img') }}" target="_blank"><img class="preview" src="{{ $product->getAttribute('img') }}" /></a> </td>
                <td>{{ $product->getAttribute('title') }}</td>
                <td><input type="text" class="input" value="{{ $product->getAttribute('russian_title') }}" data-id="{{ $product->getAttribute('id') }}" data-attribute="russian_title" /></td>
                <td>{{ $product->getAttribute('help_html') }}</td>
                <td><textarea class="input" data-id="{{ $product->getAttribute('id') }}" data-attribute="russian_help_html">{{ $product->getAttribute('russian_title') }}</textarea></td>
                <td><a href="{{ route('save_product', ['id' => $product->getAttribute('id')]) }}" class="btn btn-success" data-action="save" data-id="{{ $product->getAttribute('id') }}">Сохранить</a> </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-center">
        <a href="{{ route('save_all_products') }}" class="btn btn-success btn-lg" data-action="save-all">Сохранить все</a>
    </div>
    {{ $products->links() }}
@endsection