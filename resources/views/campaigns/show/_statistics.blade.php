<div class="flex flex-col gap-4">
    <x-alert noIcon success :title="__(
        'Your campaign was sent to ' .
            $query['total_subscribers'] .
            ' subscribers of the list: ' .
            $campaign->emailList->title,
    )" />

    <div class="grid grid-cols-3 gap-5">
        <x-dashboard.card :heading="$query['total_openings'] ?? 0" subheading="{{ __('Opens') }}" />

        <x-dashboard.card :heading="$query['unique_openings']" subheading="{{ __('Unique Opens') }}" />

        <x-dashboard.card heading="{{ $query['openings_rate'] ?? 0 }}%" subheading="{{ __('Open Rate') }}" />

        <x-dashboard.card :heading="$query['total_clicks'] ?? 0" subheading="{{ __('Clicks') }}" />

        <x-dashboard.card :heading="$query['unique_clicks']" subheading="{{ __('Unique Clicks') }}" />

        <x-dashboard.card heading="{{ $query['clicks_rate'] ?? 0 }}%" subheading="{{ __('Clicks Rate') }}" />
    </div>
</div>
