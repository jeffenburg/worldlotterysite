@csrf
<div class="form-grid">
    <div class="card">
        <div class="field">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $lottery->name) }}" required @class(['is-invalid' => $errors->has('name')])>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="slug">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $lottery->slug) }}" @class(['mono', 'is-invalid' => $errors->has('slug')])>
            <div class="hint">Leave blank to generate from the name. Used in the public URL.</div>
            @error('slug') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="country">Country</label>
            <input type="text" id="country" name="country" value="{{ old('country', $lottery->country) }}">
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="tall">{{ old('description', $lottery->description) }}</textarea>
        </div>
    </div>

    <div>
        <div class="card">
            <h2>Status</h2>
            <div class="field">
                <label class="checkbox">
                    <input type="checkbox" name="active" value="1" @checked(old('active', $lottery->active))>
                    Active (visible on the public site)
                </label>
            </div>
        </div>

        <div class="card">
            <h2>Jackpot &amp; draw</h2>
            <div class="field-row">
                <div class="field">
                    <label for="jackpot">Jackpot</label>
                    <input type="number" step="0.01" min="0" id="jackpot" name="jackpot" value="{{ old('jackpot', $lottery->jackpot) }}">
                </div>
                <div class="field">
                    <label for="jackpot_currency">Currency</label>
                    <input type="text" id="jackpot_currency" name="jackpot_currency" maxlength="3" placeholder="USD" value="{{ old('jackpot_currency', $lottery->jackpot_currency) }}">
                </div>
            </div>
            <div class="field">
                <label for="jackpot_usd">Jackpot (USD)</label>
                <input type="number" step="0.01" min="0" id="jackpot_usd" name="jackpot_usd" value="{{ old('jackpot_usd', $lottery->jackpot_usd) }}">
                <div class="hint">Used for ordering on the public site.</div>
            </div>
            <div class="field">
                <label for="next_draw_at">Next draw</label>
                <input type="datetime-local" id="next_draw_at" name="next_draw_at" value="{{ old('next_draw_at', $lottery->next_draw_at?->format('Y-m-d\TH:i')) }}">
            </div>
            <div class="field-row">
                <div class="field">
                    <label for="main_numbers_count">Main numbers</label>
                    <input type="number" min="0" max="99" id="main_numbers_count" name="main_numbers_count" value="{{ old('main_numbers_count', $lottery->main_numbers_count) }}">
                </div>
                <div class="field">
                    <label for="bonus_numbers_count">Bonus numbers</label>
                    <input type="number" min="0" max="99" id="bonus_numbers_count" name="bonus_numbers_count" value="{{ old('bonus_numbers_count', $lottery->bonus_numbers_count) }}">
                </div>
            </div>
            <div class="hint">Jackpot and draw values are normally kept up to date by the scraper.</div>
        </div>

        @if ($lottery->exists && $lottery->thelotter_slug)
            <div class="card">
                <h2>Scraper source</h2>
                <div class="muted">theLotter: <span class="mono">{{ $lottery->thelotter_slug }}</span>@if ($lottery->thelotter_id) (#{{ $lottery->thelotter_id }})@endif</div>
            </div>
        @endif
    </div>
</div>

<div class="form-footer">
    <a href="{{ route('admin.lotteries.index') }}" class="btn">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $lottery->exists ? 'Save changes' : 'Create lottery' }}</button>
</div>
