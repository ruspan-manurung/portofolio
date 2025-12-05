namespace CateringSchedule.Models
{
    public class Schedule
    {
        public int Id { get; set; }
        public string CompanyName { get; set; }
        public string ContactPerson { get; set; }
        public string Phone { get; set; }
        public string Email { get; set; }
        public string Contract { get; set; }
        public DateTime StartDate { get; set; }
    }
}
