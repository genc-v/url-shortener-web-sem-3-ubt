using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Cors;
using Microsoft.AspNetCore.Mvc;
using System.Security.Claims;
using URLShortener.Database;
using URLShortener.DTOs;
using URLShortener.DTOs.User;
using URLShortener.ModelHelpers;
using URLShortener.Models;
using URLShortener.Service;
using URLShortener.Service.User;

namespace URLShortener.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    [EnableCors]
    public class UserController : Controller
    {
        private readonly IUserService _userService;

        public UserController(IUserService userService)
        {
            _userService = userService;
        }

        [HttpGet]
        public IActionResult GetUsers()
        {
            var users = _userService.GetAllUsers();
            if (users.Any())
            {
                return Ok(users);
            }
            return NotFound("No user exists in the database");
        }

        [HttpGet("{id}")]
        public IActionResult GetUser(int id)
        {
            var user = _userService.GetUserById(id);
            if (user != null)
            {
                return Ok(user);
            }
            return NotFound($"User with ID {id} not found");
        }

        [HttpGet("/Urls/{token}")]
        public IActionResult GetUserWithUrls(string token, int pageNumber = 1, int pageSize = 10)
        {
            int userId = Authentication.GetUserIdFromToken(token);
            var userUrls = _userService.GetUserWithUrls(userId);
            if (userUrls == null || userUrls.Urls == null)
            {
                return NotFound("No URLs found for the user");
            }

            var totalUrls = userUrls.Urls.Count();
            var totalPages = (int)Math.Ceiling((double)totalUrls / pageSize);
            var pagedUrls = userUrls.Urls.Skip((pageNumber - 1) * pageSize).Take(pageSize).ToList();

            var response = new
            {
                Urls = pagedUrls,
                TotalPages = totalPages
            };

            return Ok(response);
        }
        
        [HttpPost("login")]
        [AllowAnonymous]
        public IActionResult Login([FromBody] LoginModel request)
        {
           
            var token = _userService.Login(request);
      
            if (token == null || token == string.Empty)
            {
                return BadRequest(new { message = "UserName or Password is incorrect" });
            }

            return Ok( token );
       
        }
        
        [HttpPost("signup")]
        [AllowAnonymous]
        public IActionResult Signup([FromBody] SignUpModel request)
        { 

            var newUser = _userService.AddUser(request);

            if (newUser != null)
            {
                return Ok(newUser);
            }

            return Conflict("Email is already taken");

        }
        
        [HttpPut("{id}")]
        public IActionResult UpdateUser(int id, UserUpdate userInput)
        {

            var updatedUser = _userService.UpdateUser(id, userInput);

            if (updatedUser != null)
            {
                return Ok(updatedUser);
            }

            return NotFound($"User with ID {id} not found");
        }

        [HttpDelete]
        public IActionResult DeleteUser(int id) 
        {

            _userService.DeleteUser(id);
            return Ok($"User with ID {id} deleted successfully");

        }

        [HttpGet("isAdmin")]
        public bool isAdmin(string token)
        {

            bool isAdmin = Authentication.IsAdminFromToken(token);
            return isAdmin;

        }
    }
}
