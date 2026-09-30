@php($tinymceKey = config('services.tinymce.api_key'))
@if ($tinymceKey)
    <script src="https://cdn.tiny.cloud/1/{{ $tinymceKey }}/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
    <script>
        tinymce.init({
            selector: 'textarea.rich-editor',
            plugins: 'advlist anchor autolink autoresize charmap code fullscreen image link lists media searchreplace table visualblocks wordcount',
            menubar: 'edit view insert format table',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist blockquote | link image media table | code fullscreen',
            min_height: 420,
            max_height: 900,
            block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4',
            link_default_target: '_blank',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            branding: false,
            promotion: false,
            content_style: 'body { font-family: system-ui, sans-serif; font-size: 15px; line-height: 1.6; max-width: 46rem; margin: 1rem auto; }',
        });
    </script>
@else
    <script>
        document.querySelectorAll('textarea.rich-editor').forEach(function (el) {
            var hint = document.createElement('div');
            hint.className = 'error';
            hint.textContent = 'Rich text editor disabled: TINYMCE_API_KEY is not set in the server environment.';
            el.insertAdjacentElement('afterend', hint);
        });
    </script>
@endif
