<div>
    <form action="{{ url('/donasi') }}" method="post">
        @csrf
        <input type="number" name="nominal">
        <button type="submit">OK</button>
    </form>
</div>
