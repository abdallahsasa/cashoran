import { SharedData } from './global';

// pages/course-player
export interface CoursePlayerProps extends SharedData {
   type: string;
   course: Course;
   section: CourseSection;
   reviews: Pagination<CourseReview>;
   watching: SectionLesson | SectionQuiz;
   watchHistory: WatchHistory;
   totalContent: number;
   userReview: CourseReview | null;
   totalReviews: CourseTotalReview;
   zoomConfig: ZoomConfigFields;
}

// pages/intro
export interface IntroPageProps extends SharedData {
   page: Page;
   type: 'intro' | 'demo';
   customize: boolean;

   courses: Pagination<Course>;
   reviews: Pagination<CourseReview>;

   topCourse: Course;
   topCourses: Course[];
   newCourses: Course[];
   topReviews: CourseReview[];

   instructor: Instructor;
   instructors: Pagination<Instructor>;
   topInstructors: Instructor[];

   topCategories: CourseCategory[];
   categoryTopCourses: CourseCategory[];

   latestCourses: Course[];
   heroCourses: Course[];
   blogs: Blog[];
}

// pages/student/index
export interface StudentDashboardProps extends SharedData {
   tab: string;
   status?: string;
   instructor: Instructor;
   enrollments?: Enrollment[];
   wishlists?: CourseWishlist[];
   hasVerifiedEmail: boolean;
}

// pages/settings/pages
export interface PageSelectProps extends SharedData {
   pages: Page[];
   home: Settings<PageFields>;
}
