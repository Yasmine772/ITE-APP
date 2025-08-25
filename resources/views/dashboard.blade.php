
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        function fetchNotifications() {
            $.ajax({
                url: '/api/notifications/unread',
                method: 'GET',
                success: function (response) {

                    const notifications = response.data || [];

                    const list = $('#notificationList');
                    list.empty();

                    if (notifications.length === 0) {
                        list.append('<li class="dropdown-item text-center text-muted">لا توجد إشعارات</li>');
                    } else {
                        notifications.forEach(notification => {
                            list.append(
                                `<li class="dropdown-item">
                                        <strong>${notification.data.title}</strong><br>
                                        <small>${notification.data.message}</small>
                                    </li>`
                            );
                        });
                    }

                   // $('#notificationCount').text(notifications.length);
                },
                error: function () {
                    console.error('فشل تحميل الإشعارات');
                }
            });
        }

        $(document).ready(fetchNotifications);
    </script>

    </div>
</x-app-layout>


