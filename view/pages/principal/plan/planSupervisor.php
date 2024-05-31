<script src='view/assets/node_modules/fullcalendar/index.global.js'></script>

<style>
    
    .custom-event {
            display: flex;
            align-items: center;
        }
        .event-button {
            flex: 1;
            cursor: pointer;
        }
        .delete-button {
            margin-left: 5px;
            cursor: pointer;
            background: transparent;
            border: none;
            color: red;
        }
        .fc-event {
            color: white !important; /* Ensure text is white for better readability */
        }
</style>

<div class="card-custom">
    <div class="card-header-custom">
        <strong>Plan de superviciones</strong>
    </div>
</div>

<div class="card mt-5">
    <div id='calendar' class="p-3"></div>
</div>

<BUTTON onclick="seeInspectionsback()"></BUTTON>
    
<script src="view/assets/js/ajax/General/getPlansSupervisor.js"></script>