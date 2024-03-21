@extends(backpack_view('blank'))

@php
    if (backpack_theme_config('show_getting_started')) {
        $widgets['before_content'][] = [
            'type'        => 'view',
            'view'        => backpack_view('inc.getting_started'),
        ];
    } else {
       
        $userCount = \App\Models\User::count();
        $patients = \App\Models\Patients::count();
    //add div row using 'div' widget and make other widgets inside it to be in a row
    Widget::add()->to('before_content')->type('div')->class('row')->content([

        //widget made using the array definition
        Widget::make(
            [
                'type'          => 'progress_white',
                'class'         => 'card mb-2',
                'value'         => $userCount,
                'description'   => 'Registered users.',
                'progress'      => (100 * (int)$userCount / 1000),
                'progressClass' => 'progress-bar bg-primary',
                'hint'          => '8544 more until next milestone.',
            ]

        ),
        Widget::make(
            [
                'type'          => 'progress_white',
                'class'         => 'card mb-2',
                'value'         => $patients,
                'description'   => 'Patients',
                'progress'      => (100 * (int)$patients / 1000),
                'progressClass' => 'progress-bar bg-primary',
                'hint'          => 'Number of patients listed.',
            ]

        ),
    ]);

    //you can also add Script & CSS to your page using 'script' & 'style' widget
    Widget::add()->type('script')->stack('after_scripts')->content('https://code.jquery.com/ui/1.12.0/jquery-ui.min.js');
    Widget::add()->type('style')->stack('after_styles')->content('https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.0.0-beta.58/dist/themes/light.css');
    }
@endphp

@section('content')
@endsection
