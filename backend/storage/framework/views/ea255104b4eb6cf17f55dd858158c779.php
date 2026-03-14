<?php $__env->startSection('title', 'Admin - Classes'); ?>
<?php $__env->startSection('dashboard-content'); ?>

<style>
/* --- Global --- */
body, html {
    background:#ffffff !important;
    color:#1e293b;
    font-family:'Inter', sans-serif;
    margin:0;
    padding:0;
}

/* --- Header --- */
.page-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:30px;
}
.page-title{
    font-size:28px;
    font-weight:800;
    color:#0f172a;
}
.create-btn{
    background:linear-gradient(135deg,#2563eb,#0ea5e9);
    color:white;
    border:none;
    padding:12px 22px;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
    transition:all .25s;
    box-shadow:0 4px 14px rgba(37,99,235,.3);
}
.create-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(37,99,235,.4);
}

/* --- Search & Filter --- */
.search-filter{
    display:flex;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:20px;
}
.search-input{
    flex:1;
    min-width:180px;
    padding:10px 14px;
    border-radius:8px;
    border:1px solid #e2e8f0;
    font-size:14px;
    outline:none;
    transition:border .2s;
}
.search-input:focus{border-color:#2563eb;}

/* --- Grid Cards --- */
.classes-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:20px;
}

/* --- Card --- */
.class-card{
    background:#ffffff;
    border-radius:16px;
    padding:20px;
    box-shadow:0 10px 40px rgba(0,0,0,.08);
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    transition:all .25s;
    cursor:pointer;
    position:relative;
}
.class-card:hover{
    transform:translateY(-5px);
    box-shadow:0 16px 50px rgba(0,0,0,.15);
}

/* --- Card content --- */
.class-name{
    font-size:18px;
    font-weight:700;
    margin-bottom:12px;
    color:#0f172a;
}
.badge{
    display:inline-block;
    padding:4px 10px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    color:white;
    margin-right:6px;
}
.badge-professor{background:#2563eb;}
.badge-room{background:#34d399;}

/* --- Actions --- */
.card-actions{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:15px;
}
.edit-btn{
    background:#fbbf24;
    border:none;
    padding:6px 12px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:all .2s;
}
.edit-btn:hover{background:#f59e0b;}
.delete-btn{
    background:#ef4444;
    border:none;
    padding:6px 12px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
}
.delete-btn:hover{background:#dc2626;}

/* --- Pagination --- */
.pagination{
    display:flex;
    justify-content:center;
    margin-top:25px;
    gap:8px;
}
.pagination a, .pagination span{
    padding:8px 12px;
    border-radius:8px;
    border:1px solid #e2e8f0;
    font-size:14px;
    font-weight:500;
    text-decoration:none;
    color:#1e293b;
    transition:all .2s;
}
.pagination a:hover{background:#f1f5f9;}

/* --- Modal --- */
.modal-bg{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(15,23,42,.35);
    backdrop-filter:blur(6px);
    justify-content:center;
    align-items:center;
    z-index:999;
}
.modal-card{
    background:white;
    padding:30px;
    border-radius:18px;
    width:420px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    animation:pop .3s ease;
    position:relative;
}
.modal-title{
    font-size:20px;
    font-weight:700;
    margin-bottom:20px;
    color:#0f172a;
}
.modal-input{
    width:100%;
    padding:10px 14px;
    border-radius:8px;
    border:1px solid #e2e8f0;
    margin-bottom:12px;
    font-size:14px;
}
.modal-input:focus{border-color:#2563eb;outline:none;}
.modal-close{
    position:absolute;
    right:18px;
    top:15px;
    font-size:22px;
    border:none;
    background:none;
    cursor:pointer;
    color:#64748b;
}
.modal-close:hover{color:#ef4444;}
@keyframes pop{0%{transform:scale(.85);opacity:0}100%{transform:scale(1);opacity:1}}
</style>


<div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Gestion des Classes</h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Gérez et suivez toutes les classes de l'établissement</div>
        </div>
        <button id="showCreateFormBtn" class="create-btn" style="margin-left:auto;">+ Créer une classe</button>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.2rem;margin-bottom:2.2rem;">
        <div style="background:#fff;border-radius:1rem;padding:1.3rem 1.2rem;box-shadow:0 2px 8px 0 rgba(30,41,59,0.08);display:flex;align-items:center;gap:.8rem;">
            <span style="background:#e0e7ff;color:#2563eb;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="3" stroke="#2563eb" stroke-width="2" fill="#fff"/></svg>
            </span>
            <div>
                <div style="font-size:1.5rem;font-weight:700;"><?php echo e($stats['total'] ?? $classes->count()); ?></div>
                <div style="color:#64748b;font-size:.98rem;">Total classes</div>
            </div>
        </div>
        <div style="background:#fff;border-radius:1rem;padding:1.3rem 1.2rem;box-shadow:0 2px 8px 0 rgba(30,41,59,0.08);display:flex;align-items:center;gap:.8rem;">
            <span style="background:#d1fae5;color:#059669;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#059669" stroke-width="2" fill="#fff"/><path d="M9 12l2 2 4-4" stroke="#059669" stroke-width="2" fill="none"/></svg>
            </span>
            <div>
                <div style="font-size:1.5rem;font-weight:700;"><?php echo e($stats['active'] ?? '-'); ?></div>
                <div style="color:#64748b;font-size:.98rem;">Actives</div>
            </div>
        </div>
        <div style="background:#fff;border-radius:1rem;padding:1.3rem 1.2rem;box-shadow:0 2px 8px 0 rgba(30,41,59,0.08);display:flex;align-items:center;gap:.8rem;">
            <span style="background:#e0e7ff;color:#2563eb;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M15 9l-6 6" stroke="#2563eb" stroke-width="2" fill="none"/></svg>
            </span>
            <div>
                <div style="font-size:1.5rem;font-weight:700;"><?php echo e($stats['inactive'] ?? '-'); ?></div>
                <div style="color:#64748b;font-size:.98rem;">Inactives</div>
            </div>
        </div>
        <div style="background:#fff;border-radius:1rem;padding:1.3rem 1.2rem;box-shadow:0 2px 8px 0 rgba(30,41,59,0.08);display:flex;align-items:center;gap:.8rem;">
            <span style="background:#fef3c7;color:#d97706;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M12 8v4l3 3" stroke="#d97706" stroke-width="2" fill="none"/><circle cx="12" cy="12" r="10" stroke="#d97706" stroke-width="2" fill="#fff"/></svg>
            </span>
            <div>
                <div style="font-size:1.5rem;font-weight:700;"><?php echo e($stats['special'] ?? '-'); ?></div>
                <div style="color:#64748b;font-size:.98rem;">Spéciales</div>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:1rem;margin-bottom:2.2rem;flex-wrap:wrap;">
        <input type="text" id="searchInput" placeholder="Rechercher une classe..." class="search-input">
        <select id="filterProfessor" class="search-input">
            <option value="">Tous les professeurs</option>
            <?php $__currentLoopData = $professors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $professor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($professor->name); ?>"><?php echo e($professor->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select id="filterRoom" class="search-input">
            <option value="">Toutes les salles</option>
            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($room->name); ?>"><?php echo e($room->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="classes-grid" id="classesGrid">
    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="class-card" data-name="<?php echo e(strtolower($class->name)); ?>" data-professor="<?php echo e(strtolower($class->professor?->name ?? '')); ?>" data-room="<?php echo e(strtolower($class->room?->name ?? '')); ?>" style="background:#fff;border-radius:1.1rem;box-shadow:0 2px 8px 0 rgba(30,41,59,0.08);padding:1.5rem 1.3rem;display:flex;flex-direction:column;gap:.7rem;position:relative;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div style="font-size:1.18rem;font-weight:700;color:#1e293b;"><?php echo e($class->name); ?></div>
            </div>
        </div>
        <div style="margin-top:.3rem;display:flex;gap:.7rem;flex-wrap:wrap;align-items:center;">
            <?php if($class->professor): ?>
                <span style="background:#2563eb;color:#fff;padding:.2rem .7rem;border-radius:.5rem;font-size:.93rem;font-weight:500;"><?php echo e($class->professor->name); ?></span>
            <?php endif; ?>
            <?php if($class->room): ?>
                <span style="background:#34d399;color:#fff;padding:.2rem .7rem;border-radius:.5rem;font-size:.93rem;font-weight:500;"><?php echo e($class->room->name); ?></span>
            <?php endif; ?>
        </div>
        <div style="display:flex;gap:1rem;margin-top:.7rem;align-items:center;">
            <button class="edit-btn">Edit</button>
            <form method="POST" action="<?php echo e(route('admin.classes.destroy', $class)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button class="delete-btn" type="submit">Delete</button>
            </form>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="pagination"><?php echo e($classes->links()); ?></div>
</div>

<!-- --- Modal Créer Classe --- -->
<div id="modalBackdrop" class="modal-bg">
    <form method="POST" action="<?php echo e(route('admin.classes.store')); ?>" class="modal-card">
        <?php echo csrf_field(); ?>
        <button type="button" id="closeCreateFormBtn" class="modal-close">×</button>
        <div class="modal-title">Créer une classe</div>
        <input name="name" placeholder="Nom de la classe" required class="modal-input">
        <input name="description" placeholder="Description" class="modal-input">
        <select name="professor_id" class="modal-input">
            <option value="">Professeur</option>
            <?php $__currentLoopData = $professors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $professor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($professor->id); ?>"><?php echo e($professor->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="room_id" class="modal-input">
            <option value="">Salle</option>
            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($room->id); ?>"><?php echo e($room->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="create-btn" style="width:100%">Créer</button>
    </form>
</div>

<script>
// Modal
const modal = document.getElementById("modalBackdrop")
document.getElementById("showCreateFormBtn").onclick=()=>{modal.style.display="flex"}
document.getElementById("closeCreateFormBtn").onclick=()=>{modal.style.display="none"}
modal.onclick=(e)=>{if(e.target===modal){modal.style.display="none"}}

// Search & Filter
const searchInput = document.getElementById('searchInput')
const filterProfessor = document.getElementById('filterProfessor')
const filterRoom = document.getElementById('filterRoom')
const cards = document.querySelectorAll('.class-card')

function filterCards(){
    const searchVal = searchInput.value.toLowerCase()
    const profVal = filterProfessor.value.toLowerCase()
    const roomVal = filterRoom.value.toLowerCase()
    cards.forEach(card=>{
        const name = card.dataset.name
        const prof = card.dataset.professor
        const room = card.dataset.room
        if(name.includes(searchVal) && (prof.includes(profVal) || profVal==='') && (room.includes(roomVal) || roomVal==='')){
            card.style.display='flex'
        } else {
            card.style.display='none'
        }
    })
}

searchInput.addEventListener('input',filterCards)
filterProfessor.addEventListener('change',filterCards)
filterRoom.addEventListener('change',filterCards)
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/dashboard/admin/classes/index.blade.php ENDPATH**/ ?>