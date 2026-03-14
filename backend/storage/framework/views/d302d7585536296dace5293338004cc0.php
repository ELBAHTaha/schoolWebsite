<?php $__env->startSection('title', 'Admin - Rooms'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Gestion des Salles</h1>
                <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Gérez et suivez toutes les salles de l'établissement</div>
            </div>
            <button class="create-btn" style="margin-left:auto;">+ Ajouter une salle</button>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.2rem;margin-bottom:2.2rem;">
            <div style="background:#fff;border-radius:1rem;padding:1.3rem 1.2rem;box-shadow:0 2px 8px 0 rgba(30,41,59,0.08);display:flex;align-items:center;gap:.8rem;">
                <span style="background:#e0e7ff;color:#2563eb;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="3" stroke="#2563eb" stroke-width="2" fill="#fff"/></svg>
                </span>
                <div>
                    <div style="font-size:1.5rem;font-weight:700;"><?php echo e($stats['total'] ?? '0'); ?></div>
                    <div style="color:#64748b;font-size:.98rem;">Total salles</div>
                </div>
            </div>
            <div style="background:#fff;border-radius:1rem;padding:1.3rem 1.2rem;box-shadow:0 2px 8px 0 rgba(30,41,59,0.08);display:flex;align-items:center;gap:.8rem;">
                <span style="background:#d1fae5;color:#059669;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#059669" stroke-width="2" fill="#fff"/><path d="M9 12l2 2 4-4" stroke="#059669" stroke-width="2" fill="none"/></svg>
                </span>
                <div>
                    <div style="font-size:1.5rem;font-weight:700;"><?php echo e($stats['available'] ?? '0'); ?></div>
                    <div style="color:#64748b;font-size:.98rem;">Disponibles</div>
                </div>
            </div>
            <div style="background:#fff;border-radius:1rem;padding:1.3rem 1.2rem;box-shadow:0 2px 8px 0 rgba(30,41,59,0.08);display:flex;align-items:center;gap:.8rem;">
                <span style="background:#e0e7ff;color:#2563eb;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M15 9l-6 6" stroke="#2563eb" stroke-width="2" fill="none"/></svg>
                </span>
                <div>
                    <div style="font-size:1.5rem;font-weight:700;"><?php echo e($stats['occupied'] ?? '0'); ?></div>
                    <div style="color:#64748b;font-size:.98rem;">Occupées</div>
                </div>
            </div>
            <div style="background:#fff;border-radius:1rem;padding:1.3rem 1.2rem;box-shadow:0 2px 8px 0 rgba(30,41,59,0.08);display:flex;align-items:center;gap:.8rem;">
                <span style="background:#fef3c7;color:#d97706;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M12 8v4l3 3" stroke="#d97706" stroke-width="2" fill="none"/><circle cx="12" cy="12" r="10" stroke="#d97706" stroke-width="2" fill="#fff"/></svg>
                </span>
                <div>
                    <div style="font-size:1.5rem;font-weight:700;"><?php echo e($stats['maintenance'] ?? '0'); ?></div>
                    <div style="color:#64748b;font-size:.98rem;">Maintenance</div>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:1rem;margin-bottom:2.2rem;flex-wrap:wrap;">
            <input type="text" placeholder="Rechercher une salle..." style="flex:1 1 300px;min-width:220px;padding:.9rem 1.2rem;border-radius:.7rem;border:1px solid #e5e7eb;font-size:1.08rem;background:#fff;">
            <select style="padding:.9rem 1.2rem;border-radius:.7rem;border:1px solid #e5e7eb;font-size:1.08rem;background:#fff;">
                <option>Tous les statuts</option>
                <option>Disponible</option>
                <option>Occupée</option>
                <option>Maintenance</option>
            </select>
            <select style="padding:.9rem 1.2rem;border-radius:.7rem;border:1px solid #e5e7eb;font-size:1.08rem;background:#fff;">
                <option>Tous les types</option>
                <option>Salle de cours</option>
                <option>Laboratoire</option>
                <option>Amphithéâtre</option>
                <option>Salle de réunion</option>
            </select>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(370px,1fr));gap:1.3rem;">
            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="background:#fff;border-radius:1.1rem;box-shadow:0 2px 8px 0 rgba(30,41,59,0.08);padding:1.5rem 1.3rem;display:flex;flex-direction:column;gap:.7rem;position:relative;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <div style="font-size:1.18rem;font-weight:700;color:#1e293b;"><?php echo e($room->name); ?></div>
                        </div>
                        <?php if($room->status === 'Disponible'): ?>
                            <span style="background:#d1fae5;color:#059669;padding:.3rem .9rem;border-radius:.7rem;font-size:.98rem;font-weight:600;">Disponible</span>
                        <?php elseif($room->status === 'Occupée'): ?>
                            <span style="background:#e0e7ff;color:#2563eb;padding:.3rem .9rem;border-radius:.7rem;font-size:.98rem;font-weight:600;">Occupée</span>
                        <?php elseif($room->status === 'Maintenance'): ?>
                            <span style="background:#fef3c7;color:#d97706;padding:.3rem .9rem;border-radius:.7rem;font-size:.98rem;font-weight:600;">Maintenance</span>
                        <?php endif; ?>
                    </div>
                    <div style="margin-top:.3rem;display:flex;gap:.7rem;flex-wrap:wrap;align-items:center;">
                        <span style="color:#64748b;font-size:.98rem;display:flex;align-items:center;gap:.3rem;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke="#64748b" stroke-width="2"/></svg> Capacité de la salle:  <?php echo e($room->capacity ?? '?'); ?> places</span>
                        <?php if($room->features && in_array('Projecteur', $room->features)): ?>
                            <span style="color:#64748b;font-size:.98rem;display:flex;align-items:center;gap:.3rem;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#64748b" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="#64748b"/></svg> Projecteur</span>
                        <?php endif; ?>
                        <?php if($room->features && in_array('Wi-Fi', $room->features)): ?>
                            <span style="color:#64748b;font-size:.98rem;display:flex;align-items:center;gap:.3rem;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M2 12s4-8 10-8 10 8 10 8M6 16s2-4 6-4 6 4 6 4M10 20h4" stroke="#64748b" stroke-width="2"/></svg> Wi-Fi</span>
                        <?php endif; ?>
                    </div>
                    <div style="display:flex;gap:1rem;margin-top:.7rem;align-items:center;">
                        <form method="POST" action="<?php echo e(route('admin.rooms.destroy', $room)); ?>" style="display:inline;">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn" style="background:#dc2626;" type="submit">Supprimer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div style="display:flex;justify-content:center;margin-top:1.2rem;"><?php echo e($rooms->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/dashboard/admin/rooms/index.blade.php ENDPATH**/ ?>