@extends('layout')

@section('title')Управление парсером@endsection

@section('content')
   <div>
      <button class="btn btn-success btn-lg{{ $can_run ? '' : ' disabled' }} run-parser" id="run_parser">Запустить парсер</button>
   </div>

   {{ $executions->links() }}
   <table class="table table-bordered table-striped">
      <thead>
         <tr>
            <th colspan="6" class="text-center">Запуски парсера</th>
         </tr>
         <tr>
            <th>Время запуска</th>
            <th>Время завершения</th>
            <th>Успешное выполнение</th>
            <th>Спарсено категорий</th>
            <th>Спарсено страниц</th>
            <th>Спарсено товаров</th>
         </tr>
      </thead>
      <tbody>
         @foreach($executions as $execution)
            <tr>
               <td>{{ $execution->startTime }}</td>
               <td>
                  {{ $execution->finishTime }}
               </td>
               <td>{{ $execution->isSuccess }}</td>
               <td>{{ $execution->getAttribute('categories_count') }}</td>
               <td>{{ $execution->getAttribute('pages_count') }}</td>
               <td>{{ $execution->getAttribute('cproducts_count') }}</td>
            </tr>
         @endforeach
      </tbody>
   </table>
   {{ $executions->links() }}
@endsection