import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { ThemeProvider } from "./contexts/ThemeContext";
import { AuthProvider } from "./contexts/AuthContext";
import { ProtectedRoute } from "./components/ProtectedRoute";
import Index from "./pages/Index";
import About from "./pages/About";
import Programs from "./pages/Programs";
import Contact from "./pages/Contact";
import PreRegistration from "./pages/PreRegistration";
import Login from "./pages/Login";
import AdminDashboard from "./pages/dashboard/AdminDashboard";
import UsersManagement from "./pages/dashboard/UsersManagement";
import PaymentsOverview from "./pages/dashboard/PaymentsOverview";
import Announcements from "./pages/dashboard/Announcements";
import StudentDashboard from "./pages/dashboard/StudentDashboard";
import ProfessorDashboard from "./pages/dashboard/ProfessorDashboard";
import SecretaryDashboard from "./pages/dashboard/SecretaryDashboard";
import AdminClasses from "./pages/dashboard/sections/AdminClasses";
import AdminRooms from "./pages/dashboard/sections/AdminRooms";
import AdminSettings from "./pages/dashboard/sections/AdminSettings";
import StudentMyCourses from "./pages/dashboard/sections/StudentMyCourses";
import StudentDocuments from "./pages/dashboard/sections/StudentDocuments";
import StudentHomework from "./pages/dashboard/sections/StudentHomework";
import StudentTimetable from "./pages/dashboard/sections/StudentTimetable";
import StudentPaymentStatus from "./pages/dashboard/sections/StudentPaymentStatus";
import StudentSettings from "./pages/dashboard/sections/StudentSettings";
import ProfessorMyClasses from "./pages/dashboard/sections/ProfessorMyClasses";
import ProfessorDocuments from "./pages/dashboard/sections/ProfessorDocuments";
import ProfessorHomework from "./pages/dashboard/sections/ProfessorHomework";
import ProfessorTimetable from "./pages/dashboard/sections/ProfessorTimetable";
import ProfessorSettings from "./pages/dashboard/sections/ProfessorSettings";
import SecretaryStudents from "./pages/dashboard/sections/SecretaryStudents";
import SecretaryAddStudent from "./pages/dashboard/sections/SecretaryAddStudent";
import SecretaryClasses from "./pages/dashboard/sections/SecretaryClasses";
import SecretaryPayments from "./pages/dashboard/sections/SecretaryPayments";
import SecretaryReceipts from "./pages/dashboard/sections/SecretaryReceipts";
import SecretarySettings from "./pages/dashboard/sections/SecretarySettings";
import CommercialDashboard from "./pages/dashboard/CommercialDashboard";
import NotFound from "./pages/NotFound";

const queryClient = new QueryClient();

const App = () => (
  <QueryClientProvider client={queryClient}>
    <AuthProvider>
      <TooltipProvider>
        <Toaster />
        <Sonner />
        <BrowserRouter>
        <Routes>
        <Route path="/" element={<Index />} />
        <Route path="/about" element={<About />} />
        <Route path="/programs" element={<Programs />} />
        <Route path="/contact" element={<Contact />} />
        <Route path="/pre-registration" element={<PreRegistration />} />
        <Route path="/login" element={<Login />} />
          <Route path="/dashboard" element={<Navigate to="/dashboard/admin" replace />} />
          <Route path="/dashboard/admin" element={<ProtectedRoute allowedRoles={['admin','directeur']}><AdminDashboard /></ProtectedRoute>} />
          <Route path="/dashboard/admin/users" element={<ProtectedRoute allowedRoles={['admin','directeur']}><UsersManagement /></ProtectedRoute>} />
          <Route
            path="/dashboard/admin/classes"
            element={<ProtectedRoute allowedRoles={['admin','directeur']}><AdminClasses /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/admin/rooms"
            element={<ProtectedRoute allowedRoles={['admin','directeur']}><AdminRooms /></ProtectedRoute>}
          />
          <Route path="/dashboard/admin/payments" element={<ProtectedRoute allowedRoles={['admin','directeur']}><PaymentsOverview /></ProtectedRoute>} />
          <Route path="/dashboard/admin/announcements" element={<ProtectedRoute allowedRoles={['admin','directeur']}><Announcements role="admin" /></ProtectedRoute>} />
          <Route
            path="/dashboard/admin/settings"
            element={<ProtectedRoute allowedRoles={['admin','directeur']}><AdminSettings /></ProtectedRoute>}
          />
          <Route path="/dashboard/student" element={<ProtectedRoute allowedRoles={['student']}><StudentDashboard /></ProtectedRoute>} />
          <Route
            path="/dashboard/student/settings"
            element={<ProtectedRoute allowedRoles={['student']}><StudentSettings /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/student/my-courses"
            element={<ProtectedRoute allowedRoles={['student']}><StudentMyCourses /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/student/documents"
            element={<ProtectedRoute allowedRoles={['student']}><StudentDocuments /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/student/homework"
            element={<ProtectedRoute allowedRoles={['student']}><StudentHomework /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/student/timetable"
            element={<ProtectedRoute allowedRoles={['student']}><StudentTimetable /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/student/payment-status"
            element={<ProtectedRoute allowedRoles={['student']}><StudentPaymentStatus /></ProtectedRoute>}
          />
          <Route path="/dashboard/student/announcements" element={<ProtectedRoute allowedRoles={['student']}><Announcements role="student" /></ProtectedRoute>} />
          <Route path="/dashboard/professor" element={<ProtectedRoute allowedRoles={['professor']}><ProfessorDashboard /></ProtectedRoute>} />
          <Route
            path="/dashboard/professor/settings"
            element={<ProtectedRoute allowedRoles={['professor']}><ProfessorSettings /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/professor/my-classes"
            element={<ProtectedRoute allowedRoles={['professor']}><ProfessorMyClasses /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/professor/documents"
            element={<ProtectedRoute allowedRoles={['professor']}><ProfessorDocuments /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/professor/homework"
            element={<ProtectedRoute allowedRoles={['professor']}><ProfessorHomework /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/professor/timetable"
            element={<ProtectedRoute allowedRoles={['professor']}><ProfessorTimetable /></ProtectedRoute>}
          />
          <Route path="/dashboard/professor/announcements" element={<ProtectedRoute allowedRoles={['professor']}><Announcements role="professor" /></ProtectedRoute>} />
          <Route path="/dashboard/secretary" element={<ProtectedRoute allowedRoles={['secretary']}><SecretaryDashboard /></ProtectedRoute>} />
          <Route
            path="/dashboard/secretary/settings"
            element={<ProtectedRoute allowedRoles={['secretary']}><SecretarySettings /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/secretary/students"
            element={<ProtectedRoute allowedRoles={['secretary']}><SecretaryStudents /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/secretary/add-student"
            element={<ProtectedRoute allowedRoles={['secretary']}><SecretaryAddStudent /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/secretary/classes"
            element={<ProtectedRoute allowedRoles={['secretary']}><SecretaryClasses /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/secretary/payments"
            element={<ProtectedRoute allowedRoles={['secretary']}><SecretaryPayments /></ProtectedRoute>}
          />
          <Route
            path="/dashboard/secretary/receipts"
            element={<ProtectedRoute allowedRoles={['secretary']}><SecretaryReceipts /></ProtectedRoute>}
          />
          <Route path="/dashboard/secretary/announcements" element={<ProtectedRoute allowedRoles={['secretary']}><Announcements role="secretary" /></ProtectedRoute>} />
          <Route path="/dashboard/commercial" element={<ProtectedRoute allowedRoles={['commercial']}><CommercialDashboard /></ProtectedRoute>} />
          <Route path="/dashboard/commercial/leads" element={<ProtectedRoute allowedRoles={['commercial']}><CommercialDashboard /></ProtectedRoute>} />
          <Route path="*" element={<NotFound />} />
        </Routes>
      </BrowserRouter>
    </TooltipProvider>
    </AuthProvider>
  </QueryClientProvider>
);

export default App;
