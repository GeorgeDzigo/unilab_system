
@if($entry->qr_path)

    <a href="{{ $entry->getQrSrc() }}" target="_blank">
        <img src="{{ $entry->getQrSrc() }}" style="
              max-height: 25px;
              width: auto;
              border-radius: 3px;">
    </a>

@endif
