/**
 * Nexus Kanban — HTML5 drag & drop with Livewire persistence.
 * Keeps the template's .dragging / .drag-over visual classes and
 * dispatches `taskMoved` to the Board component on drop.
 */
function initKanbanBoard() {
    const board = document.querySelector('[data-kanban-board]');
    if (!board) return;

    let draggedId = null;

    board.querySelectorAll('.kanban-card').forEach((card) => {
        card.setAttribute('draggable', 'true');

        card.addEventListener('dragstart', () => {
            draggedId = card.dataset.taskId;
            card.classList.add('dragging');
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
            board.querySelectorAll('.kanban-column').forEach((c) => c.classList.remove('drag-over'));
        });
    });

    board.querySelectorAll('.kanban-column').forEach((column) => {
        const list = column.querySelector('[data-task-list]');

        column.addEventListener('dragover', (e) => {
            e.preventDefault();
            column.classList.add('drag-over');

            const dragging = board.querySelector('.kanban-card.dragging');
            if (!dragging || !list) return;

            const after = [...list.querySelectorAll('.kanban-card:not(.dragging)')]
                .find((el) => e.clientY <= el.getBoundingClientRect().top + el.offsetHeight / 2);

            after ? list.insertBefore(dragging, after) : list.appendChild(dragging);
        });

        column.addEventListener('dragleave', (e) => {
            if (!column.contains(e.relatedTarget)) column.classList.remove('drag-over');
        });

        column.addEventListener('drop', (e) => {
            e.preventDefault();
            column.classList.remove('drag-over');
            if (!draggedId || !list) return;

            const ordered = [...list.querySelectorAll('.kanban-card')].map((el) => Number(el.dataset.taskId));

            window.Livewire?.dispatch('taskMoved', {
                taskId: Number(draggedId),
                columnId: Number(column.dataset.columnId),
                ordered,
            });
            draggedId = null;
        });
    });
}

document.addEventListener('livewire:init', () => {
    // Re-bind after every Livewire DOM morph (task created/edited/moved)
    Livewire.hook('morph.updated', () => queueMicrotask(initKanbanBoard));
});
document.addEventListener('DOMContentLoaded', initKanbanBoard);
