@extends('layouts.master-layout')

@section('title', 'Activity Logs')

@section('top-nav-links')
<a href="{{route('admin.index')}}" class="hover:bg-blue-300 px-4 py-1 border-2 border-black rounded-lg text-black font-semibold text-sm">Home</a>
@endsection

@section('content')
    <div class="container mx-auto px-4 sm:px-2">
        <div class="overflow-x-auto overflow-y-auto">
            <table class="min-w-full bg-white divide-y divide-gray-200">
                <thead class="bg-gray-50">
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
