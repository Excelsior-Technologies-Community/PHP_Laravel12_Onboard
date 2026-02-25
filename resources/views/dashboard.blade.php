<x-app-layout>

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .step {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }

        .step-complete {
            background: #e8f5e9;
            border-color: #4CAF50;
        }

        .step-pending {
            background: #fff3e0;
            border-color: #ff9800;
        }

        .step-title {
            font-size: 18px;
        }

        .btn {
            padding: 8px 15px;
            background: #2196F3;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background: #1976D2;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .progress {
            height: 100%;
            background: #4CAF50;
            border-radius: 10px;
            text-align: center;
            color: white;
            font-size: 12px;
        }

        .complete {
            color: #4CAF50;
            font-weight: bold;
        }

        .pending {
            color: #ff9800;
            font-weight: bold;
        }
    </style>

    <div class="container">
        <div class="card">
            <div class="title">Dashboard Onboarding</div>

            @php
                $user = auth()->user()->fresh();

                // Define steps manually with completion check
                $steps = [
                    [
                        'title' => 'Complete Profile',
                        'link' => '/profile',
                        'cta' => 'Complete Profile',
                        'completed' => optional($user->profile)->completed ?? false,
                    ],
                    [
                        'title' => 'Create First Post',
                        'link' => '/post/create',
                        'cta' => 'Create Post',
                        'completed' => $user->posts()->exists(),
                    ],
                ];

                $total = count($steps);
                $completed = collect($steps)->filter(fn($step) => $step['completed'])->count();
                $percent = $total > 0 ? ($completed / $total) * 100 : 0;
            @endphp

            <div class="progress-bar">
                <div class="progress" style="width: {{ round($percent) }}%">
                    {{ round($percent) }}%
                </div>
            </div>

            @foreach($steps as $step)
                <div class="step {{ $step['completed'] ? 'step-complete' : 'step-pending' }}">
                    <div class="step-title">
                        @if($step['completed'])
                            <span class="complete">✅ {{ $step['title'] }}</span>
                        @else
                            <span class="pending">⬜ {{ $step['title'] }}</span>
                        @endif
                    </div>
                    @if(!$step['completed'])
                        <a href="{{ $step['link'] }}" class="btn">{{ $step['cta'] }}</a>
                    @endif
                </div>
            @endforeach

            @if($completed === $total)
                <div class="complete" style="margin-top:20px;font-size:18px;">
                    🎉 Onboarding Completed Successfully!
                </div>
            @endif
        </div>
    </div>

</x-app-layout>