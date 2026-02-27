<div class="section-header-breadcrumb">
  @foreach ($list as $key => $value)
    @if ($key == count($list) - 1)
      <div class="breadcrumb-item active">{{ $value }}</div>
    @else
      <div class="breadcrumb-item">{{ $value }}</div>
    @endif
  @endforeach
</div>