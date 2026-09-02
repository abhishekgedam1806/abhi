<div class="card-box">
    <div class="card-box-header">
        <h3 class="card-box-title">
            <i class="fa fa-list text-primary"></i> {{ $colTitle }} Links ({{ count($items) }})
        </h3>
        <button type="button" class="btn-add-primary" onclick="openAddFooterModal('{{ $menuType }}', '{{ addslashes($colTitle) }}')">
            <i class="fa fa-plus"></i> Add Link to {{ $colTitle }}
        </button>
    </div>

    <div class="table-responsive">
        <table class="table-menu">
            <thead>
                <tr>
                    <th style="width: 70px;">Order</th>
                    <th>Link Title</th>
                    <th>Target URL / Path</th>
                    <th style="text-align: center; width: 110px;">Status</th>
                    <th style="text-align: right; width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr id="item-row-{{ $item->id }}">
                    <td>
                        <input type="number" class="form-control-custom order-input" data-id="{{ $item->id }}" value="{{ $item->order_num }}" style="width: 55px; padding: 4px 6px; text-align: center; font-weight: 700;">
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #0F172A;">{{ $item->title }}</div>
                    </td>
                    <td>
                        <code style="background: #F1F5F9; color: #0F172A; padding: 3px 6px; border-radius: 4px; font-size: 12px;">{{ $item->url }}</code>
                        @if($item->target === '_blank')
                        <span style="font-size: 11px; color: #64748B;" title="Opens in new tab"><i class="fa fa-external-link"></i></span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <button type="button" class="status-toggle-btn {{ $item->is_active ? 'active' : '' }}" onclick="toggleItemStatus(this, {{ $item->id }})" title="Click to toggle Active / Inactive">
                            <span class="toggle-track">
                                <span class="toggle-thumb"></span>
                            </span>
                            <span class="toggle-text">{{ $item->is_active ? 'Active' : 'Inactive' }}</span>
                        </button>
                    </td>
                    <td style="text-align: right;">
                        <button type="button" class="btn-action-icon" onclick='openEditFooterModal(@json($item))' title="Edit Link">
                            <i class="fa fa-pencil text-primary"></i>
                        </button>
                        <form action="{{ route('admin.menu.delete', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this footer link?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action-icon text-danger" title="Delete Link">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94A3B8; padding: 30px;">
                        <i class="fa fa-folder-open-o" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                        No links in this column. Click "Add Link" above to add one.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(count($items) > 0)
    <div style="margin-top: 16px; text-align: right;">
        <button type="button" class="btn-action-icon" onclick="saveOrderNums()" style="width: auto; padding: 6px 14px; font-weight: 700; color: #03855c;">
            <i class="fa fa-save"></i> Save Order Numbers
        </button>
    </div>
    @endif
</div>
