<div class="col-12">
    <div class="card">
        <div class="card-body">
            <form method="get" action="{{ route($currentRoute) }}">
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label class="sr-only" for="email">E-mail</label>
                        <div class="input-group mb-2">
                            <input type="text"
                                   value="{{ $query->get('email') }}"
                                   class="form-control"
                                   name="email"
                                   id="email"
                                   placeholder="E-mail">
                        </div>
                    </div>
                    <div class="col-auto">
                        <label class="sr-only" for="full_name">ФИО</label>
                        <div class="input-group mb-2">
                            <input type="text"
                                   value="{{ $query->get('full_name') }}"
                                   class="form-control"
                                   name="full_name"
                                   id="full_name"
                                   placeholder="ФИО">
                        </div>
                    </div>
                    <div class="col-auto">
                        <label for="verified" class="sr-only">Подтвержден</label>
                        <div class="input-group mb-2">
                            <select class="form-control"
                                    id="verified"
                                    name="verified">
                                <option value="all" {{ $query->get('verified') == 'all' ? 'selected' : '' }}>
                                    -Любой-
                                </option>
                                <option value="1" {{ $query->get('verified') == '1' ? 'selected' : '' }}>
                                    Подтвержден
                                </option>
                                <option value="0" {{ $query->get('verified') == '0' ? 'selected' : '' }}>
                                    Ожидает
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group mb-2"
                             role="group">
                            <button type="submit" class="btn btn-primary">Применить</button>
                            <a href="{{ route($currentRoute) }}" class="btn btn-secondary">Сбросить</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>