<x-app-layout>

<div class="section-heading">Reports</div>

<div style="display: flex; flex-direction: column; gap: 10px;">

    <a href="{{ route('reports.monthly') }}"
       style="background: #fff; border-radius: 14px; padding: 18px 16px; display: flex; align-items: center; justify-content: space-between; text-decoration: none; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
        <div>
            <div style="font-size: 15px; font-weight: 700; color: #1a1a2e;">Monthly Report</div>
            <div style="font-size: 12px; color: #9ca3af; margin-top: 3px;">Spending breakdown with charts</div>
        </div>
        <span style="color: #9ca3af; font-size: 18px;">&#8250;</span>
    </a>

    <a href="{{ route('coach.index') }}"
       style="background: #fff; border-radius: 14px; padding: 18px 16px; display: flex; align-items: center; justify-content: space-between; text-decoration: none; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
        <div>
            <div style="font-size: 15px; font-weight: 700; color: #1a1a2e;">Budget Coach</div>
            <div style="font-size: 12px; color: #9ca3af; margin-top: 3px;">Spending vs limits per category</div>
        </div>
        <span style="color: #9ca3af; font-size: 18px;">&#8250;</span>
    </a>

    <a href="{{ route('recurring.index') }}"
       style="background: #fff; border-radius: 14px; padding: 18px 16px; display: flex; align-items: center; justify-content: space-between; text-decoration: none; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
        <div>
            <div style="font-size: 15px; font-weight: 700; color: #1a1a2e;">Recurring Expenses</div>
            <div style="font-size: 12px; color: #9ca3af; margin-top: 3px;">Manage your monthly bills</div>
        </div>
        <span style="color: #9ca3af; font-size: 18px;">&#8250;</span>
    </a>

</div>

</x-app-layout>
