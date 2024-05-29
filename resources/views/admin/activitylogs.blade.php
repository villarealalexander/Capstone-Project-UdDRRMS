@extends('layouts.master-layout')

@section('title', 'Activity Logs')

@section('top-nav-links')
<a href="{{route('admin.index')}}" class="hover:bg-blue-500 px-2 rounded-lg text-white font-semibold text-md mx-2">
         <i class="fa-solid fa-house mr-1"></i>Back to Home
    </a>
@endsection

@section('content')
    <div class="container mx-auto px-4 sm:px-2">
        <div class="overflow-x-auto overflow-y-auto" style="height: 700px">
            <table class="min-w-full bg-white divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Role</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action Type</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @foreach($activityLogs as $log)
                        <tr>
                            <td class="px-4 py-2">{{ $log->user->name }}</td>
                            <td class="px-4 py-2">{{ $log->user_role }}</td>
                            <td class="px-4 py-2">{{ $log->action_type }}</td>
                            <td class="px-4 py-2">{{ $log->details }}</td>
                            <td class="px-4 py-2">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
