using Microsoft.AspNetCore.Authentication.JwtBearer;
using Microsoft.EntityFrameworkCore;
using Microsoft.IdentityModel.Tokens;
using Microsoft.OpenApi.Models;
using System.Text;
using URLShortener.Database;
using URLShortener.Service.Url;
using URLShortener.Service.User;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container
builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen(c =>
{
    c.SwaggerDoc("v1", new OpenApiInfo { Title = "Your API", Version = "v1" });
});

// Authentication configuration
builder.Services.AddAuthentication(JwtBearerDefaults.AuthenticationScheme)
    .AddJwtBearer(options =>
    {
        var configuration = builder.Configuration;
        options.TokenValidationParameters = new TokenValidationParameters
        {
            ValidateIssuer = true,
            ValidateAudience = true,
            ValidateLifetime = true,
            ValidateIssuerSigningKey = true,
            ValidIssuer = configuration["Jwt:Issuer"],
            ValidAudience = configuration["Jwt:Audience"],
            IssuerSigningKey = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(configuration["Jwt:Key"]))
        };
    });

// Authorization policies
builder.Services.AddAuthorization(options =>
{
    options.AddPolicy("AdminRole", policy => policy.RequireRole("admin"));
    options.AddPolicy("UserPolicy", policy => policy.RequireRole("user"));
});

// CORS configuration
builder.Services.AddCors(options =>
{
    var allowedOrigins = builder.Configuration.GetSection("AllowedOrigins").Get<string[]>();
    if (allowedOrigins == null || !allowedOrigins.Any())
    {
        throw new InvalidOperationException("AllowedOrigins is not configured in appsettings.json.");
    }

    options.AddPolicy("DefaultPolicy", policy =>
    {
        policy.WithOrigins(allowedOrigins)
              .AllowAnyMethod()
              .AllowAnyHeader()
              .AllowCredentials(); // Only use this if credentials (e.g., cookies or Authorization headers) are needed
    });
});

// Database configuration
builder.Services.AddDbContext<UrlShortenerDbContext>(options =>
    options.UseNpgsql(builder.Configuration.GetConnectionString("Database")));

// Dependency injection
builder.Services.AddScoped<IUrlService, UrlService>();
builder.Services.AddScoped<IUrlValidationService, UrlValidationService>();
builder.Services.AddScoped<IUserService, UserService>();

var app = builder.Build();

// Configure the HTTP request pipeline
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI(c => c.SwaggerEndpoint("/swagger/v1/swagger.json", "Your API V1"));
}

app.UseHttpsRedirection();
app.UseCors("DefaultPolicy");
app.UseAuthentication();
app.UseAuthorization();
app.MapControllers();
app.Run();
